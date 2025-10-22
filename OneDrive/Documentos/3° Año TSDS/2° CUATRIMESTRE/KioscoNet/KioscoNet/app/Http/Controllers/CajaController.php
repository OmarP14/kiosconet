<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    /**
     * Mostrar el listado de movimientos de caja
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            // Obtener filtros de la request
            $fechaDesde = $request->get('fecha_desde', now()->startOfMonth()->toDateString());
            $fechaHasta = $request->get('fecha_hasta', now()->toDateString());
            $tipo = $request->get('tipo');
            $usuarioId = $request->get('usuario_id');

            // Consulta base con joins para obtener nombre del usuario
            $query = DB::table('caja')
                    ->leftJoin('usuarios', 'caja.usuario_id', '=', 'usuarios.id')
                    ->select('caja.*', 'usuarios.nombre as usuario_nombre')
                    ->whereBetween(DB::raw('DATE(caja.created_at)'), [$fechaDesde, $fechaHasta]);

            // Aplicar filtros
            if ($tipo) {
                $query->where('caja.tipo', $tipo);
            }

            if ($usuarioId) {
                $query->where('caja.usuario_id', $usuarioId);
            }

            // Ordenar por fecha descendente y paginar
            $movimientos = $query->orderBy('caja.created_at', 'desc')->paginate(20);

            // Calcular estadísticas del período actual
            $estadisticas = $this->calcularEstadisticas($fechaDesde, $fechaHasta);

            // Obtener usuarios para el filtro
            $usuarios = DB::table('usuarios')
                         ->select('id', 'nombre as name')
                         ->whereNull('deleted_at')
                         ->orderBy('nombre')
                         ->get();

            return view('caja.index', compact(
                'movimientos',
                'usuarios',
                'fechaDesde', 
                'fechaHasta'
            ) + $estadisticas);

        } catch (\Exception $e) {
            Log::error('Error en CajaController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => Auth::id()
            ]);

            // ✅ CORREGIDO: Crear paginación vacía correctamente
            $movimientosVacios = new \Illuminate\Pagination\LengthAwarePaginator(
                [],           // Items (array vacío)
                0,            // Total items
                20,           // Items por página
                1,            // Página actual
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return view('caja.index', [
                'movimientos' => $movimientosVacios,  // ✅ CAMBIO AQUÍ
                'usuarios' => collect(),
                'saldoActual' => 0,
                'ingresosHoy' => 0,
                'egresosHoy' => 0,
                'ventasHoy' => 0,
                'fechaDesde' => $fechaDesde ?? now()->startOfMonth()->toDateString(),
                'fechaHasta' => $fechaHasta ?? now()->toDateString()
            ])->withErrors(['error' => 'Error al cargar los movimientos de caja: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario para crear movimiento
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $saldoActual = $this->calcularSaldoActual();
        return view('caja.create', compact('saldoActual'));
    }

    /**
     * Almacenar un nuevo movimiento de caja
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|min:3|max:255',
            'monto' => 'required|numeric|min:0.01|max:999999.99',
        ], [
            'tipo.required' => 'Debe especificar el tipo de movimiento.',
            'tipo.in' => 'El tipo de movimiento debe ser ingreso o egreso.',
            'concepto.required' => 'El concepto es obligatorio.',
            'concepto.min' => 'El concepto debe tener al menos 3 caracteres.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número válido.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'monto.max' => 'El monto no puede ser mayor a $999,999.99.',
        ]);

        DB::beginTransaction();

        try {
            // Obtener saldo actual antes del movimiento
            $saldoAnterior = $this->calcularSaldoActual();
            
            // Calcular nuevo saldo
            $nuevoSaldo = $request->tipo === 'ingreso' 
                ? $saldoAnterior + $request->monto
                : $saldoAnterior - $request->monto;

            // Advertencia para saldo negativo en egresos
            if ($request->tipo === 'egreso' && $nuevoSaldo < 0) {
                Log::warning('Movimiento de egreso deja saldo negativo', [
                    'usuario_id' => Auth::id(),
                    'saldo_anterior' => $saldoAnterior,
                    'monto_egreso' => $request->monto,
                    'saldo_resultante' => $nuevoSaldo
                ]);
            }

            // Crear el movimiento
            $movimientoId = DB::table('caja')->insertGetId([
                'usuario_id' => Auth::id(),
                'tipo' => $request->tipo,
                'concepto' => $request->concepto,
                'descripcion' => $request->descripcion,
                'monto' => $request->monto,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log del movimiento
            Log::info('Movimiento de caja registrado', [
                'movimiento_id' => $movimientoId,
                'tipo' => $request->tipo,
                'concepto' => $request->concepto,
                'monto' => $request->monto,
                'usuario_id' => Auth::id(),
                'saldo_anterior' => $saldoAnterior,
                'saldo_nuevo' => $nuevoSaldo
            ]);

            DB::commit();

            $mensaje = ucfirst($request->tipo) . " de $" . number_format($request->monto, 2) . " registrado exitosamente.";
            
            if ($request->tipo === 'egreso' && $nuevoSaldo < 0) {
                $mensaje .= " Advertencia: El saldo de caja quedó en negativo.";
            }

            return redirect()->route('caja.index')
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error al registrar movimiento de caja', [
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id(),
                'datos_request' => $request->except(['_token'])
            ]);

            return redirect()->back()
                           ->with('error', 'Error al registrar el movimiento: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Mostrar un movimiento específico
     * 
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $movimiento = DB::table('caja')
                          ->leftJoin('usuarios', 'caja.usuario_id', '=', 'usuarios.id')
                          ->select('caja.*', 'usuarios.nombre as usuario_nombre')
                          ->where('caja.id', $id)
                          ->first();

            if (!$movimiento) {
                // ✅ Si es AJAX, retornar JSON
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json(['error' => 'Movimiento no encontrado'], 404);
                }
                
                return redirect()->route('caja.index')
                               ->with('error', 'Movimiento no encontrado.');
            }

            // ✅ Si es petición AJAX, retornar JSON
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'id' => $movimiento->id,
                    'tipo' => $movimiento->tipo,
                    'concepto' => $movimiento->concepto,
                    'monto' => $movimiento->monto,
                    'created_at' => $movimiento->created_at,
                    'usuario' => [
                        'name' => $movimiento->usuario_nombre ?? 'N/A',
                        'nombre' => $movimiento->usuario_nombre ?? 'N/A'
                    ],
                    'descripcion' => null,
                    'saldo_posterior' => 0
                ]);
            }

            return view('caja.show', compact('movimiento'));

        } catch (\Exception $e) {
            Log::error('Error en CajaController@show', [
                'error' => $e->getMessage(),
                'movimiento_id' => $id,
                'usuario_id' => Auth::id()
            ]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['error' => 'Error al mostrar el movimiento'], 500);
            }

            return redirect()->route('caja.index')
                           ->with('error', 'Error al mostrar el movimiento.');
        }
    }

    /**
     * Mostrar formulario de edición
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $movimiento = DB::table('caja')->where('id', $id)->first();

            if (!$movimiento) {
                return redirect()->route('caja.index')
                               ->with('error', 'Movimiento no encontrado.');
            }

            // Verificar que no sea muy antiguo (opcional - 24 horas)
            $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
            if ($fechaMovimiento->diffInHours(now()) > 24) {
                return redirect()->route('caja.index')
                               ->with('error', 'No se pueden editar movimientos de más de 24 horas.');
            }

            $saldoActual = $this->calcularSaldoActual();
            
            return view('caja.edit', compact('movimiento', 'saldoActual'));

        } catch (\Exception $e) {
            Log::error('Error en CajaController@edit', [
                'error' => $e->getMessage(),
                'movimiento_id' => $id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()->route('caja.index')
                           ->with('error', 'Error al cargar el movimiento para editar.');
        }
    }

    /**
     * Actualizar un movimiento
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'concepto' => 'required|string|min:3|max:255',
            'monto' => 'required|numeric|min:0.01|max:999999.99',
        ]);

        DB::beginTransaction();

        try {
            $movimiento = DB::table('caja')->where('id', $id)->first();

            if (!$movimiento) {
                return redirect()->route('caja.index')
                               ->with('error', 'Movimiento no encontrado.');
            }

            // Verificar que no sea muy antiguo
            $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
            if ($fechaMovimiento->diffInHours(now()) > 24) {
                return redirect()->route('caja.index')
                               ->with('error', 'No se pueden editar movimientos de más de 24 horas.');
            }

            // Actualizar el movimiento
            DB::table('caja')
              ->where('id', $id)
              ->update([
                  'concepto' => $request->concepto,
                  'monto' => $request->monto,
                  'updated_at' => now(),
              ]);

            Log::info('Movimiento de caja actualizado', [
                'movimiento_id' => $id,
                'concepto_anterior' => $movimiento->concepto,
                'concepto_nuevo' => $request->concepto,
                'monto_anterior' => $movimiento->monto,
                'monto_nuevo' => $request->monto,
                'usuario_id' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('caja.index')
                           ->with('success', 'Movimiento actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error al actualizar movimiento de caja', [
                'error' => $e->getMessage(),
                'movimiento_id' => $id,
                'usuario_id' => Auth::id()
            ]);

            return redirect()->back()
                           ->with('error', 'Error al actualizar el movimiento: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Eliminar un movimiento de caja
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $movimiento = DB::table('caja')->where('id', $id)->first();

            if (!$movimiento) {
                return redirect()->route('caja.index')
                               ->with('error', 'Movimiento no encontrado.');
            }

            // Verificar que no sea muy antiguo (opcional)
            $fechaMovimiento = \Carbon\Carbon::parse($movimiento->created_at);
            if ($fechaMovimiento->diffInHours(now()) > 24) {
                return redirect()->route('caja.index')
                               ->with('error', 'No se pueden eliminar movimientos de más de 24 horas.');
            }

            $concepto = $movimiento->concepto;
            $monto = $movimiento->monto;
            $tipo = $movimiento->tipo;

            // Log antes de eliminar
            Log::warning('Movimiento de caja eliminado', [
                'movimiento_id' => $id,
                'concepto' => $concepto,
                'tipo' => $tipo,
                'monto' => $monto,
                'usuario_elimino' => Auth::id(),
                'fecha_original' => $movimiento->created_at
            ]);

            DB::table('caja')->where('id', $id)->delete();

            DB::commit();

            return redirect()->route('caja.index')
                           ->with('success', "Movimiento '{$concepto}' eliminado exitosamente.");

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error al eliminar movimiento de caja', [
                'movimiento_id' => $id,
                'error' => $e->getMessage(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()->back()
                           ->with('error', 'Error al eliminar el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar arqueo de caja
     * 
     * @return \Illuminate\View\View
     */
    public function arqueo()
    {
        try {
            $hoy = now()->toDateString();
            
            // Movimientos del día
            $movimientos = DB::table('caja')
                           ->leftJoin('usuarios', 'caja.usuario_id', '=', 'usuarios.id')
                           ->select('caja.*', 'usuarios.nombre as usuario_nombre')
                           ->whereDate('caja.created_at', $hoy)
                           ->orderBy('caja.created_at', 'desc')
                           ->get();

            $ingresos = $movimientos->where('tipo', 'ingreso');
            $egresos = $movimientos->where('tipo', 'egreso');
            
            $totalIngresos = $ingresos->sum('monto');
            $totalEgresos = $egresos->sum('monto');
            $saldoDelDia = $totalIngresos - $totalEgresos;
            $saldoActual = $this->calcularSaldoActual();

            // Ventas del día (desde la tabla ventas)
            $ventasHoy = DB::table('ventas')
                          ->whereDate('created_at', $hoy)
                          ->sum('total') ?? 0;
                          
            $ventasEfectivo = DB::table('ventas')
                            ->whereDate('created_at', $hoy)
                            ->where('metodo_pago', 'efectivo')
                            ->sum('total') ?? 0;

            return view('caja.arqueo', compact(
                'movimientos',
                'ingresos', 
                'egresos',
                'totalIngresos',
                'totalEgresos',
                'saldoDelDia',
                'saldoActual',
                'ventasHoy',
                'ventasEfectivo'
            ));

        } catch (\Exception $e) {
            Log::error('Error en arqueo de caja', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => Auth::id()
            ]);

            return view('caja.arqueo', [
                'movimientos' => collect(),
                'ingresos' => collect(),
                'egresos' => collect(),
                'totalIngresos' => 0,
                'totalEgresos' => 0,
                'saldoDelDia' => 0,
                'saldoActual' => 0,
                'ventasHoy' => 0,
                'ventasEfectivo' => 0
            ])->withErrors(['error' => 'Error al cargar el arqueo de caja.']);
        }
    }

    /**
     * Cerrar caja
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cerrarCaja(Request $request)
    {
        $request->validate([
            'monto_fisico' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();

        try {
            $montoFisico = $request->monto_fisico;
            $saldoSistema = $this->calcularSaldoActual();
            $diferencia = $montoFisico - $saldoSistema;

            // Crear registro de cierre
            $cierreId = DB::table('caja')->insertGetId([
                'usuario_id' => Auth::id(),
                'tipo' => 'ingreso',
                'concepto' => 'Cierre de caja - ' . now()->format('d/m/Y') . 
                             ' | Físico: $' . number_format($montoFisico, 2) .
                             ' | Sistema: $' . number_format($saldoSistema, 2) .
                             ' | Dif: $' . number_format($diferencia, 2) .
                             ($request->observaciones ? ' | ' . $request->observaciones : ''),
                'monto' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Si hay diferencia significativa, registrar ajuste
            if (abs($diferencia) > 0.01) {
                $tipoAjuste = $diferencia > 0 ? 'ingreso' : 'egreso';
                $montoAjuste = abs($diferencia);
                
                DB::table('caja')->insert([
                    'usuario_id' => Auth::id(),
                    'tipo' => $tipoAjuste,
                    'concepto' => 'Ajuste por diferencia en cierre de caja',
                    'monto' => $montoAjuste,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::warning('Diferencia en cierre de caja', [
                    'usuario_id' => Auth::id(),
                    'monto_fisico' => $montoFisico,
                    'saldo_sistema' => $saldoSistema,
                    'diferencia' => $diferencia
                ]);
            }

            DB::commit();

            $mensaje = "Cierre de caja realizado exitosamente.";
            if (abs($diferencia) > 0.01) {
                $mensaje .= " Se registró una diferencia de $" . number_format(abs($diferencia), 2);
            }

            return redirect()->route('caja.index')
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error al cerrar caja', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => Auth::id()
            ]);

            return redirect()->back()
                           ->with('error', 'Error al realizar cierre de caja: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Calcular saldo actual de caja
     * 
     * @return float
     */
    protected function calcularSaldoActual()
    {
        try {
            $ingresos = DB::table('caja')->where('tipo', 'ingreso')->sum('monto') ?? 0;
            $egresos = DB::table('caja')->where('tipo', 'egreso')->sum('monto') ?? 0;
            
            return floatval($ingresos - $egresos);
        } catch (\Exception $e) {
            Log::error('Error al calcular saldo actual', [
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Calcular estadísticas para el dashboard
     * 
     * @param string $fechaDesde
     * @param string $fechaHasta
     * @return array
     */
    protected function calcularEstadisticas($fechaDesde, $fechaHasta)
    {
        try {
            $hoy = now()->toDateString();

            return [
                'saldoActual' => $this->calcularSaldoActual(),
                'ingresosHoy' => DB::table('caja')
                                  ->where('tipo', 'ingreso')
                                  ->whereDate('created_at', $hoy)
                                  ->sum('monto') ?? 0,
                'egresosHoy' => DB::table('caja')
                                 ->where('tipo', 'egreso')
                                 ->whereDate('created_at', $hoy)
                                 ->sum('monto') ?? 0,
                'ventasHoy' => DB::table('ventas')
                                ->whereDate('created_at', $hoy)
                                ->sum('total') ?? 0
            ];
        } catch (\Exception $e) {
            Log::error('Error al calcular estadísticas', [
                'error' => $e->getMessage()
            ]);

            return [
                'saldoActual' => 0,
                'ingresosHoy' => 0,
                'egresosHoy' => 0,
                'ventasHoy' => 0
            ];
        }
    }
}
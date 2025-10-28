<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;

class ClienteController extends Controller
{
    /**
     * Mostrar listado de clientes
     */
    public function index(Request $request)
    {
        $query = Cliente::with(['ventas' => function($q) {
            $q->latest()->limit(5);
        }]);

        // Aplicar filtros
        // ✅ BÚSQUEDA MEJORADA: Incluye apellido
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('apellido', 'like', "%{$buscar}%")  // ✅ AGREGADO
                ->orWhere('telefono', 'like', "%{$buscar}%")
                ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado === 'activo');
        }

        if ($request->filled('saldo_cc')) {
            switch ($request->saldo_cc) {
                case 'con_saldo':
                    $query->where('saldo_cc', '>', 0);
                    break;
                case 'deudores':
                    $query->where('saldo_cc', '<', 0);
                    break;
                case 'sin_movimientos':
                    $query->where('saldo_cc', '=', 0);
                    break;
            }
        }

        // Ordenamiento
        switch ($request->get('orden', 'nombre')) {
            case 'reciente':
                $query->latest();
                break;
            case 'saldo_desc':
                $query->orderBy('saldo_cc', 'desc');
                break;
            case 'saldo_asc':
                $query->orderBy('saldo_cc', 'asc');
                break;
            default:
                $query->orderBy('nombre');
        }

        $clientes = $query->paginate(15);

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Almacenar nuevo cliente
     * ✅ MEJORADO: Usa StoreClienteRequest para validación
     */
        public function store(StoreClienteRequest $request)
        {
            // ✅ La validación ya se realizó en StoreClienteRequest

            try {
                $cliente = Cliente::create([
                    'nombre' => $request->nombre,
                    'apellido' => $request->apellido,           // ✅ AGREGADO
                    'telefono' => $request->telefono,
                    'email' => $request->email,
                    'direccion' => $request->direccion,
                    'tipo_cliente' => $request->tipo_cliente ?? 'minorista',  // ✅ AGREGADO
                    'limite_credito' => $request->limite_credito ?? 0,
                    'saldo_cc' => 0,
                    'activo' => $request->has('activo'),
                ]);

                Log::info('Cliente creado', [
                    'cliente_id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                    'apellido' => $cliente->apellido,           // ✅ AGREGADO AL LOG
                    'usuario_id' => auth()->id()
                ]);

                return redirect()->route('clientes.index')
                            ->with('success', "Cliente '{$cliente->nombre} {$cliente->apellido}' creado exitosamente.");

            } catch (\Exception $e) {
                Log::error('Error al crear cliente', [
                    'error' => $e->getMessage(),
                    'datos' => $request->except(['_token']),
                    'usuario_id' => auth()->id()
                ]);

                return redirect()->back()
                            ->with('error', 'Error al crear el cliente: ' . $e->getMessage())
                            ->withInput();
            }
        }

    /**
     * Mostrar detalle del cliente
     */
    public function show(Cliente $cliente)
    {
        $cliente->load([
            'ventas' => function($q) {
                $q->with('detalles.producto')->latest()->limit(10);
            }
        ]);

        // Estadísticas del cliente
        $estadisticas = [
            'total_compras' => $cliente->ventas->sum('total'),
            'cantidad_compras' => $cliente->ventas->count(),
            'promedio_compra' => $cliente->ventas->avg('total') ?? 0,
            'ultima_compra' => $cliente->ventas->first()?->created_at,
            'compras_credito' => $cliente->ventas->where('metodo_pago', 'cc')->count(),
            'productos_favoritos' => $this->getProductosFavoritos($cliente->id),
        ];

        return view('clientes.show', compact('cliente', 'estadisticas'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar cliente
     */
        /**
         * ✅ MEJORADO: Usa UpdateClienteRequest para validación
         */
        public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        // ✅ La validación ya se realizó en UpdateClienteRequest

        try {
            $datosAnteriores = $cliente->toArray();
            
            $cliente->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,           // ✅ AGREGADO
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'tipo_cliente' => $request->tipo_cliente ?? 'minorista',  // ✅ AGREGADO
                'limite_credito' => $request->limite_credito ?? 0,
                'activo' => $request->has('activo'),
            ]);

            Log::info('Cliente actualizado', [
                'cliente_id' => $cliente->id,
                'datos_anteriores' => $datosAnteriores,
                'datos_nuevos' => $cliente->fresh()->toArray(),
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('clientes.index')
                        ->with('success', "Cliente '{$cliente->nombre} {$cliente->apellido}' actualizado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al actualizar cliente', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
                'usuario_id' => auth()->id()
            ]);

            return redirect()->back()
                        ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Eliminar cliente
     */
    public function destroy(Cliente $cliente)
    {
        // Verificar permisos
        if (!auth()->user()->can('eliminar_clientes')) {
            return redirect()->back()
                           ->with('error', 'No tiene permisos para eliminar clientes.');
        }

        DB::beginTransaction();

        try {
            // Verificar que no tenga saldo pendiente
            if ($cliente->saldo_cc != 0) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar un cliente con saldo pendiente en cuenta corriente.');
            }

            // Verificar que no tenga ventas recientes (últimos 30 días)
            $ventasRecientes = $cliente->ventas()
                                     ->where('created_at', '>=', now()->subDays(30))
                                     ->count();

            if ($ventasRecientes > 0) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar un cliente con ventas en los últimos 30 días.');
            }

            $nombre = $cliente->nombre;
            
            // Log antes de eliminar
            Log::warning('Cliente eliminado', [
                'cliente_id' => $cliente->id,
                'nombre' => $nombre,
                'saldo_cc' => $cliente->saldo_cc,
                'total_ventas' => $cliente->ventas->sum('total'),
                'usuario_elimino' => auth()->id()
            ]);

            $cliente->delete();

            DB::commit();

            return redirect()->route('clientes.index')
                           ->with('success', "Cliente '{$nombre}' eliminado exitosamente.");

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error al eliminar cliente', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
                'usuario_id' => auth()->id()
            ]);

            return redirect()->back()
                           ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Ajustar saldo de cuenta corriente
     */
    public function ajustarSaldo(Request $request, Cliente $cliente)
    {
        $request->validate([
            'saldo_cc' => 'required|numeric',
            'motivo' => 'required|string|min:5|max:255',
        ], [
            'saldo_cc.required' => 'El saldo es obligatorio.',
            'saldo_cc.numeric' => 'El saldo debe ser un número válido.',
            'motivo.required' => 'El motivo del ajuste es obligatorio.',
            'motivo.min' => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        DB::beginTransaction();

        try {
            $saldoAnterior = $cliente->saldo_cc;
            $nuevoSaldo = $request->saldo_cc;
            $diferencia = $nuevoSaldo - $saldoAnterior;

            // Actualizar saldo
            $cliente->update(['saldo_cc' => $nuevoSaldo]);

            // Registrar movimiento de ajuste
            // Aquí podrías crear una tabla de movimientos de cuenta corriente
            // o registrarlo en un log especializado

            Log::info('Ajuste de saldo cuenta corriente', [
                'cliente_id' => $cliente->id,
                'cliente_nombre' => $cliente->nombre,
                'saldo_anterior' => $saldoAnterior,
                'saldo_nuevo' => $nuevoSaldo,
                'diferencia' => $diferencia,
                'motivo' => $request->motivo,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();

            $mensaje = "Saldo ajustado exitosamente. ";
            if ($diferencia > 0) {
                $mensaje .= "Se agregó $" . number_format($diferencia, 2) . " al saldo.";
            } elseif ($diferencia < 0) {
                $mensaje .= "Se descontó $" . number_format(abs($diferencia), 2) . " del saldo.";
            } else {
                $mensaje .= "El saldo no cambió.";
            }

            return redirect()->back()
                           ->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error al ajustar saldo', [
                'cliente_id' => $cliente->id,
                'error' => $e->getMessage(),
                'usuario_id' => auth()->id()
            ]);

            return redirect()->back()
                           ->with('error', 'Error al ajustar el saldo: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Mostrar clientes con cuentas corrientes
     */
    public function cuentasCorrientes()
    {
        $clientes = Cliente::where('saldo_cc', '!=', 0)
                          ->with(['ventas' => function($q) {
                              $q->where('metodo_pago', 'cc')->latest()->limit(5);
                          }])
                          ->orderBy('saldo_cc')
                          ->get();

        $resumen = [
            'total_deudores' => $clientes->where('saldo_cc', '<', 0)->count(),
            'total_acreedores' => $clientes->where('saldo_cc', '>', 0)->count(),
            'monto_total_deuda' => abs($clientes->where('saldo_cc', '<', 0)->sum('saldo_cc')),
            'monto_total_credito' => $clientes->where('saldo_cc', '>', 0)->sum('saldo_cc'),
            'balance_neto' => $clientes->sum('saldo_cc'),
        ];

        return view('clientes.cuentas-corrientes', compact('clientes', 'resumen'));
    }

    /**
     * Obtener productos favoritos del cliente
     */
    /**
 * ✅ COMPLETAMENTE CORREGIDO: Todas las referencias a detalle_venta (singular)
 */
    private function getProductosFavoritos($clienteId)
    {
        return DB::table('detalle_venta')                    // ✅ CORRECTO
                ->join('ventas', 'detalle_venta.venta_id', '=', 'ventas.id')              // ✅ CORREGIDO
                ->join('productos', 'detalle_venta.producto_id', '=', 'productos.id')     // ✅ CORREGIDO
                ->where('ventas.cliente_id', $clienteId)
                ->select('productos.nombre', DB::raw('SUM(detalle_venta.cantidad) as total_comprado'))  // ✅ CORREGIDO
                ->groupBy('productos.id', 'productos.nombre')
                ->orderBy('total_comprado', 'desc')
                ->limit(5)
                ->get();
    }

    /**
     * Estado de cuenta del cliente
     */
    public function estadoCuenta(Cliente $cliente)
    {
        $movimientos = $cliente->ventas()
                              ->where('metodo_pago', 'cc')
                              ->with('detalles.producto')
                              ->latest()
                              ->paginate(20);

        return view('clientes.estado-cuenta', compact('cliente', 'movimientos'));
    }
}
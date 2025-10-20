<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Caja;
use App\Models\PagoMixto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VentaController extends Controller
{
    // ==========================================
    // MOSTRAR PÁGINAS
    // ==========================================
    
    public function create()
    {
        $clientes = Cliente::where('activo', true)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('ventas.create', compact('clientes'));
    }

    public function index()
    {
        $ventas = Venta::with(['cliente', 'usuario'])
            ->where('anulada', false)
            ->orderBy('fecha_venta', 'desc')
            ->paginate(20);

        return view('ventas.index', compact('ventas'));
    }

    public function show($id)
    {
        $venta = Venta::with(['cliente', 'usuario', 'detalles.producto'])
            ->findOrFail($id);

        return view('ventas.show', compact('venta'));
    }

    // ==========================================
    // BUSCAR PRODUCTOS (para el buscador)
    // ==========================================
    
    public function buscarApi(Request $request)
    {
        try {
            $termino = $request->get('termino', '');
            $listaPrecio = $request->get('lista_precio', 'minorista');
            
            if (strlen($termino) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Escribe al menos 2 letras',
                    'data' => []
                ]);
            }
            
            $productos = Producto::where(function($query) use ($termino) {
                    $query->where('nombre', 'like', "%{$termino}%")
                          ->orWhere('codigo_barra', 'like', "%{$termino}%")
                          ->orWhere('descripcion', 'like', "%{$termino}%");
                })
                ->where('activo', true)
                ->limit(20)
                ->get()
                ->map(function($producto) use ($listaPrecio) {
                    $precio = match($listaPrecio) {
                        'mayorista' => $producto->precio_mayorista ?? $producto->precio,
                        'especial' => $producto->precio_especial ?? $producto->precio,
                        default => $producto->precio
                    };
                    
                    $diasHastaVencimiento = null;
                    if ($producto->fecha_vencimiento) {
                        $fechaVencimiento = Carbon::parse($producto->fecha_vencimiento);
                        $diasHastaVencimiento = Carbon::now()->diffInDays($fechaVencimiento, false);
                    }
                    
                    return [
                        'id' => $producto->id,
                        'nombre' => $producto->nombre,
                        'descripcion' => $producto->descripcion,
                        'codigo_barra' => $producto->codigo_barra,
                        'stock' => $producto->stock,
                        'stock_minimo' => $producto->stock_minimo ?? 10,
                        'precio' => $precio,
                        'precio_costo' => $producto->precio_costo ?? 0,
                        'precio_formateado' => '$' . number_format($precio, 2),
                        'fecha_vencimiento' => $producto->fecha_vencimiento,
                        'dias_hasta_vencimiento' => $diasHastaVencimiento,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $productos,
                'message' => count($productos) > 0 ? 'Productos encontrados' : 'No se encontraron productos'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // GUARDAR UNA VENTA NUEVA
    // ==========================================
    
    public function store(Request $request)
    {
        // ✅ VALIDACIÓN COMPLETA
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'nullable|date',
            'lista_precios' => 'required|in:minorista,mayorista,especial',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,cuenta_corriente,cc,mixto',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            
            // Validaciones condicionales
            'monto_recibido' => 'required_if:metodo_pago,efectivo|numeric|min:0',
            'tipo_tarjeta' => 'required_if:metodo_pago,tarjeta|in:debito,credito',
            'ultimos_digitos' => 'required_if:metodo_pago,tarjeta|digits:4',
            'codigo_autorizacion' => 'required_if:metodo_pago,tarjeta|string|max:50',
            'numero_transferencia' => 'required_if:metodo_pago,transferencia|string|max:50',
            'banco' => 'required_if:metodo_pago,transferencia|string|max:100',
            'pagos_mixtos' => 'required_if:metodo_pago,mixto|array|min:1',
        ], [
            'productos.required' => 'Debe agregar al menos un producto',
            'productos.min' => 'Debe agregar al menos un producto',
            'monto_recibido.required_if' => 'El monto recibido es obligatorio para pago en efectivo',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'pagos_mixtos.required_if' => 'Debe especificar al menos un método de pago mixto',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // ✅ VALIDAR STOCK CON MENSAJES DESCRIPTIVOS
            foreach ($request->productos as $item) {
                $producto = Producto::find($item['id']);
                
                if (!$producto) {
                    throw new \Exception("Producto ID {$item['id']} no encontrado en el sistema");
                }
                
                if (!$producto->activo) {
                    throw new \Exception("El producto '{$producto->nombre}' está inactivo y no puede venderse");
                }
                
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception(
                        "Stock insuficiente de '{$producto->nombre}'. " .
                        "Disponible: {$producto->stock} unidades, Solicitado: {$item['cantidad']} unidades"
                    );
                }
            }
            
            $cliente = Cliente::findOrFail($request->cliente_id);
            $metodoPago = $request->metodo_pago;
            
            // ✅ VALIDAR CUENTA CORRIENTE
            if (in_array($metodoPago, ['cuenta_corriente', 'cc'])) {
                if (!$cliente->limite_credito || $cliente->limite_credito == 0) {
                    throw new \Exception('Este cliente no tiene cuenta corriente habilitada');
                }
                
                $nuevoSaldo = ($cliente->saldo_cc ?? 0) + $request->total;
                if ($nuevoSaldo > $cliente->limite_credito) {
                    $disponible = $cliente->limite_credito - ($cliente->saldo_cc ?? 0);
                    throw new \Exception(
                        'Crédito insuficiente. Disponible: $' . number_format($disponible, 2) .
                        ', Necesario: $' . number_format($request->total, 2)
                    );
                }
            }
            
            // ✅ VALIDAR EFECTIVO
            if ($metodoPago === 'efectivo') {
                $montoRecibido = $request->input('monto_recibido', 0);
                if ($montoRecibido < $request->total) {
                    throw new \Exception(
                        'Monto recibido insuficiente. Total: $' . number_format($request->total, 2) .
                        ', Recibido: $' . number_format($montoRecibido, 2)
                    );
                }
            }
            
            // ✅ VALIDAR PAGO MIXTO
            if ($metodoPago === 'mixto') {
                if (!$request->has('pagos_mixtos') || empty($request->pagos_mixtos)) {
                    throw new \Exception('Debe especificar los métodos de pago mixto');
                }
                
                $totalPagos = collect($request->pagos_mixtos)->sum('monto');
                $diferencia = abs($totalPagos - $request->total);
                
                if ($diferencia > 0.01) {
                    throw new \Exception(
                        'El total de pagos mixtos no coincide. Total venta: $' . number_format($request->total, 2) .
                        ', Total pagos: $' . number_format($totalPagos, 2)
                    );
                }
            }
            
            // ✅ CREAR VENTA
            $numeroVenta = $this->generarNumeroVenta();
            
            $datosVenta = [
                'numero' => $numeroVenta,
                'usuario_id' => auth()->id(),
                'cliente_id' => $request->cliente_id,
                'fecha_venta' => $request->fecha_venta ?? now(),
                'lista_precios' => $request->lista_precios ?? 'minorista',
                'subtotal' => $request->subtotal ?? $request->total,
                'descuento_porcentaje' => $request->descuento_global ?? 0,
                'descuento_monto' => ($request->subtotal ?? $request->total) * ($request->descuento_global ?? 0) / 100,
                'total' => $request->total,
                'comision' => $request->comision ?? 0,
                'metodo_pago' => $metodoPago,
                'observaciones' => $request->observaciones,
                'estado' => 'completada',
                'anulada' => false,
            ];
            
            // Datos específicos por método de pago
            if ($metodoPago === 'efectivo') {
                $datosVenta['monto_recibido'] = $request->monto_recibido;
                $datosVenta['vuelto'] = $request->monto_recibido - $request->total;
            } 
            elseif ($metodoPago === 'tarjeta') {
                $datosVenta['tipo_tarjeta'] = $request->tipo_tarjeta;
                $datosVenta['ultimos_digitos'] = $request->ultimos_digitos;
                $datosVenta['codigo_autorizacion'] = $request->codigo_autorizacion;
            } 
            elseif ($metodoPago === 'transferencia') {
                $datosVenta['numero_transferencia'] = $request->numero_transferencia;
                $datosVenta['banco'] = $request->banco;
                $datosVenta['fecha_transferencia'] = $request->fecha_transferencia;
            } 
            elseif (in_array($metodoPago, ['cuenta_corriente', 'cc'])) {
                $datosVenta['saldo_anterior'] = $cliente->saldo_cc ?? 0;
                $datosVenta['nuevo_saldo'] = ($cliente->saldo_cc ?? 0) + $request->total;
                $datosVenta['observaciones_cc'] = $request->observaciones_cc;
            }
            elseif ($metodoPago === 'mixto') {
                $datosVenta['observaciones'] = ($request->observaciones ?? '') . ' [PAGO MIXTO]';
            }
            
            $venta = Venta::create($datosVenta);
            
            // ✅ GUARDAR DETALLES Y ACTUALIZAR STOCK
            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['id']);
                
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'precio_costo' => $item['precio_costo'] ?? $producto->precio_costo ?? 0,
                    'descuento_porcentaje' => $item['descuento'] ?? 0,
                    'descuento_monto' => $item['precio'] * ($item['descuento'] ?? 0) / 100,
                    'subtotal' => $item['subtotal'],
                ]);
                
                // Actualizar stock
                $producto->decrement('stock', $item['cantidad']);
                
                Log::info("Stock actualizado", [
                    'producto_id' => $producto->id,
                    'producto' => $producto->nombre,
                    'cantidad_vendida' => $item['cantidad'],
                    'stock_anterior' => $producto->stock + $item['cantidad'],
                    'stock_nuevo' => $producto->stock,
                    'venta_id' => $venta->id
                ]);
            }
            
            // ✅ ACTUALIZAR CUENTA CORRIENTE
            if (in_array($metodoPago, ['cuenta_corriente', 'cc'])) {
                $saldoAnterior = $cliente->saldo_cc ?? 0;
                $cliente->saldo_cc = $saldoAnterior + $request->total;
                $cliente->save();
                
                Log::info("Cuenta corriente actualizada", [
                    'cliente_id' => $cliente->id,
                    'saldo_anterior' => $saldoAnterior,
                    'cargo' => $request->total,
                    'saldo_nuevo' => $cliente->saldo_cc,
                    'venta_id' => $venta->id
                ]);
            }
            
            // ✅ PROCESAR PAGO MIXTO
            if ($metodoPago === 'mixto' && $request->has('pagos_mixtos')) {
                $totalPagos = 0;
                
                foreach ($request->pagos_mixtos as $pago) {
                    $datosPago = [
                        'venta_id' => $venta->id,
                        'metodo_pago' => $pago['metodo'],
                        'monto' => $pago['monto'],
                    ];
                    
                    // Datos específicos por método
                    if ($pago['metodo'] === 'efectivo') {
                        $datosPago['monto_recibido'] = $pago['monto'];
                    }
                    elseif ($pago['metodo'] === 'tarjeta') {
                        $datosPago['tipo_tarjeta'] = $pago['tipo_tarjeta'] ?? null;
                        $datosPago['ultimos_digitos'] = $pago['ultimos_digitos'] ?? null;
                        $datosPago['codigo_autorizacion'] = $pago['codigo_autorizacion'] ?? null;
                    }
                    elseif ($pago['metodo'] === 'transferencia') {
                        $datosPago['numero_transferencia'] = $pago['numero_transferencia'] ?? null;
                        $datosPago['banco'] = $pago['banco'] ?? null;
                        $datosPago['fecha_transferencia'] = $pago['fecha_transferencia'] ?? null;
                        $datosPago['hora_transferencia'] = $pago['hora_transferencia'] ?? null;
                    }
                    
                    PagoMixto::create($datosPago);
                    $totalPagos += $pago['monto'];
                }
                
                // Calcular vuelto si pagaron de más
                if ($totalPagos > $request->total) {
                    $vuelto = $totalPagos - $request->total;
                    $pagoEfectivo = PagoMixto::where('venta_id', $venta->id)
                        ->where('metodo_pago', 'efectivo')
                        ->first();
                    if ($pagoEfectivo) {
                        $pagoEfectivo->update(['vuelto' => $vuelto]);
                    }
                }
                
                // Registrar cada pago en caja
                $ultimaCaja = Caja::orderBy('created_at', 'desc')->first();
                $saldoAnterior = $ultimaCaja ? $ultimaCaja->saldo_actual : 0;
                
                foreach ($request->pagos_mixtos as $pago) {
                    if ($pago['metodo'] !== 'cuenta_corriente') {
                        $saldoAnterior += $pago['monto'];
                        Caja::create([
                            'usuario_id' => auth()->id(),
                            'tipo_movimiento' => 'ingreso',
                            'monto' => $pago['monto'],
                            'descripcion' => "Venta {$numeroVenta} - MIXTO (" . strtoupper($pago['metodo']) . ")",
                            'saldo_anterior' => $saldoAnterior - $pago['monto'],
                            'saldo_actual' => $saldoAnterior,
                            'venta_id' => $venta->id,
                        ]);
                    }
                }
            }
            
            // ✅ REGISTRAR EN CAJA (para pagos simples)
            if (in_array($metodoPago, ['efectivo', 'tarjeta', 'transferencia'])) {
                $ultimaCaja = Caja::orderBy('created_at', 'desc')->first();
                $saldoAnterior = $ultimaCaja ? $ultimaCaja->saldo_actual : 0;
                
                Caja::create([
                    'usuario_id' => auth()->id(),
                    'tipo_movimiento' => 'ingreso',
                    'monto' => $request->total,
                    'descripcion' => "Venta {$numeroVenta} - " . strtoupper($metodoPago),
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_actual' => $saldoAnterior + $request->total,
                    'venta_id' => $venta->id,
                ]);
            }
            
            // ✅ TODO SALIÓ BIEN
            DB::commit();
            
            Log::info("Venta procesada exitosamente", [
                'venta_id' => $venta->id,
                'numero_venta' => $numeroVenta,
                'total' => $venta->total,
                'metodo_pago' => $metodoPago,
                'usuario_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => '¡Venta procesada exitosamente!',
                'venta_id' => $venta->id,
                'numero_venta' => $numeroVenta,
                'total' => $venta->total,
                'vuelto' => $venta->vuelto ?? 0,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Error al procesar venta", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'usuario_id' => auth()->id(),
                'datos_request' => $request->except(['_token'])
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // GENERAR NÚMERO DE VENTA
    // ==========================================
    
    private function generarNumeroVenta()
    {
        $ultimaVenta = Venta::orderBy('id', 'desc')->first();
        
        if ($ultimaVenta && $ultimaVenta->numero) {
            $ultimoNumero = intval(substr($ultimaVenta->numero, 1));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }
        
        return 'V' . str_pad($nuevoNumero, 8, '0', STR_PAD_LEFT);
    }
    
    // ==========================================
    // IMPRIMIR TICKETS Y COMPROBANTES
    // ==========================================
    
    public function imprimirTicket($id)
    {
        try {
            $venta = Venta::with(['cliente', 'detalles.producto', 'usuario'])->findOrFail($id);
            
            if (!$venta->impreso) {
                $venta->marcarComoImpreso();
            }
            
            return view('ventas.ticket', compact('venta'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar ticket');
        }
    }

    public function descargarPDF($id)
    {
        try {
            $venta = Venta::with(['cliente', 'detalles.producto', 'usuario'])->findOrFail($id);
            
            $pdf = PDF::loadView('ventas.pdf', compact('venta'));
            $pdf->setPaper('a4', 'portrait');
            
            $nombreArchivo = "venta_{$venta->numero}_" . date('YmdHis') . ".pdf";
            
            return $pdf->download($nombreArchivo);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar PDF');
        }
    }

    public function comprobante($id)
    {
        try {
            $venta = Venta::with(['cliente', 'detalles.producto', 'usuario'])->findOrFail($id);
            return view('ventas.comprobante', compact('venta'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al mostrar comprobante');
        }
    }

    public function reimprimir($id)
    {
        try {
            $venta = Venta::findOrFail($id);
            
            if (!$venta->puedeReimprimir()) {
                return back()->with('error', 'No se puede reimprimir esta venta');
            }
            
            $venta->incrementarReimpresiones();
            
            return $this->imprimirTicket($id);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al reimprimir');
        }
    }

    public function consultarComprobante($token)
    {
        try {
            $venta = Venta::where('token_comprobante', $token)
                ->with(['cliente', 'detalles.producto'])
                ->firstOrFail();
            
            return view('ventas.comprobante_publico', compact('venta'));
            
        } catch (\Exception $e) {
            return view('errors.404')->with('message', 'Comprobante no encontrado');
        }
    }

    // ==========================================
    // ANULAR VENTAS
    // ==========================================
    
    public function anular(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $venta = Venta::with('detalles.producto')->findOrFail($id);

            if ($venta->anulada) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta venta ya está anulada'
                ], 400);
            }
            
            if (!$venta->puedeAnularse()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede anular (pasaron más de 24 horas)'
                ], 400);
            }

            // DEVOLVER EL STOCK
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->increment('stock', $detalle->cantidad);
            }
            
            // Revertir cuenta corriente
            if (in_array($venta->metodo_pago, ['cuenta_corriente', 'cc'])) {
                $cliente = $venta->cliente;
                $cliente->decrement('saldo_cc', $venta->total);
            }
            
            // Revertir pago mixto
            if ($venta->metodo_pago === 'mixto') {
                $pagosMixtos = PagoMixto::where('venta_id', $venta->id)->get();
                
                foreach ($pagosMixtos as $pago) {
                    if ($pago->metodo_pago !== 'cuenta_corriente') {
                        $ultimaCaja = Caja::orderBy('created_at', 'desc')->first();
                        $saldoAnterior = $ultimaCaja ? $ultimaCaja->saldo_actual : 0;
                        
                        Caja::create([
                            'usuario_id' => auth()->id(),
                            'tipo_movimiento' => 'egreso',
                            'monto' => $pago->monto,
                            'descripcion' => "Anulación venta {$venta->numero} - MIXTO (" . strtoupper($pago->metodo_pago) . ")",
                            'saldo_anterior' => $saldoAnterior,
                            'saldo_actual' => $saldoAnterior - $pago->monto,
                            'venta_id' => $venta->id,
                        ]);
                    }
                }
                
                PagoMixto::where('venta_id', $venta->id)->delete();
            }
            
            // Revertir caja para pagos simples
            if (in_array($venta->metodo_pago, ['efectivo', 'tarjeta', 'transferencia'])) {
                $ultimaCaja = Caja::orderBy('created_at', 'desc')->first();
                $saldoAnterior = $ultimaCaja ? $ultimaCaja->saldo_actual : 0;
                
                Caja::create([
                    'usuario_id' => auth()->id(),
                    'tipo_movimiento' => 'egreso',
                    'monto' => $venta->total,
                    'descripcion' => "Anulación venta {$venta->numero}",
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_actual' => $saldoAnterior - $venta->total,
                    'venta_id' => $venta->id,
                ]);
            }

            // Marcar como anulada
            $venta->update([
                'anulada' => true,
                'estado' => 'anulada',
                'usuario_anulacion_id' => auth()->id(),
                'fecha_anulacion' => now(),
                'motivo_anulacion' => $request->input('motivo_anulacion')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta anulada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al anular la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $venta = Venta::with('detalles.producto')->findOrFail($id);

            if ($venta->anulada) {
                return redirect()->back()->with('error', 'Esta venta ya fue anulada');
            }
            
            if (!$venta->puedeAnularse()) {
                return redirect()->back()->with('error', 'No se puede anular (pasaron más de 24 horas)');
            }

            DB::beginTransaction();

            // Devolver stock
            foreach ($venta->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->increment('stock', $detalle->cantidad);
            }
            
            // Revertir cuenta corriente
            if (in_array($venta->metodo_pago, ['cuenta_corriente', 'cc'])) {
                $cliente = $venta->cliente;
                $cliente->decrement('saldo_cc', $venta->total);
            }
            
            // Registrar egreso en caja
            if (in_array($venta->metodo_pago, ['efectivo', 'tarjeta', 'transferencia'])) {
                $ultimaCaja = Caja::orderBy('created_at', 'desc')->first();
                $saldoAnterior = $ultimaCaja ? $ultimaCaja->saldo_actual : 0;
                
                Caja::create([
                    'usuario_id' => auth()->id(),
                    'tipo_movimiento' => 'egreso',
                    'monto' => $venta->total,
                    'descripcion' => "Anulación venta {$venta->numero}",
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_actual' => $saldoAnterior - $venta->total,
                    'venta_id' => $venta->id,
                ]);
            }

            // Anular
            $venta->update([
                'anulada' => true,
                'estado' => 'anulada',
                'usuario_anulacion_id' => auth()->id(),
                'fecha_anulacion' => now(),
                'motivo_anulacion' => 'Venta eliminada desde el listado'
            ]);

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta anulada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al anular la venta');
        }
    }

    // ==========================================
    // FUNCIONES ÚTILES
    // ==========================================
    
    public function obtenerDetalles($id)
    {
        try {
            $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $venta->getInfoComprobante()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles'
            ], 500);
        }
    }
}
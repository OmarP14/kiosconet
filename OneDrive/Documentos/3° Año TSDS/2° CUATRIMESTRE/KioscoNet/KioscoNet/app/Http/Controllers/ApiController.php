<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    // Buscar productos para la venta
    public function buscarProductos(Request $request)
    {
        $query = $request->get('q', '');
        $listaPrecioId = $request->get('lista_precio_id', 1);

        $productos = Producto::activos()
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('codigo', 'like', "%{$query}%");
            })
            ->with(['precios' => function ($q) use ($listaPrecioId) {
                $q->where('listas_precios.id', $listaPrecioId);
            }])
            ->limit(20)
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'codigo' => $producto->codigo,
                    'stock' => $producto->stock,
                    'precio' => $producto->precios->first()->pivot->precio ?? 0,
                    'stock_bajo' => $producto->stockBajo(),
                ];
            });

        return response()->json($productos);
    }

    // Buscar clientes
    public function buscarClientes(Request $request)
    {
        $query = $request->get('q', '');

        $clientes = Cliente::where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('telefono', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($cliente) {
                return [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                    'telefono' => $cliente->telefono,
                    'saldo_cc' => $cliente->saldo_cc,
                    'tiene_deuda' => $cliente->tieneDeuda(),
                ];
            });

        return response()->json($clientes);
    }

    // Crear venta rápida
    public function crearVenta(Request $request)
    {
        try {
            $request->validate([
                'cliente_id' => 'nullable|exists:clientes,id',
                'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,billetera,cc',
                'monto_recibido' => 'required_unless:metodo_pago,cc|numeric|min:0',
                'productos' => 'required|array|min:1',
                'productos.*.id' => 'required|exists:productos,id',
                'productos.*.cantidad' => 'required|integer|min:1',
                'productos.*.precio' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            // Verificar stock antes de crear la venta
            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['id']);
                if (!$producto->tieneStock($productoData['cantidad'])) {
                    throw new \Exception("Stock insuficiente para: {$producto->nombre}");
                }
            }

            // Crear venta
            $venta = Venta::create([
                'numero' => Venta::generarNumero(),
                'usuario_id' => auth()->id(),
                'cliente_id' => $request->cliente_id,
                'metodo_pago' => $request->metodo_pago,
                'monto_recibido' => $request->monto_recibido ?? 0,
            ]);

            $total = 0;

            // Crear detalles
            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['id']);
                $subtotal = $productoData['cantidad'] * $productoData['precio'];

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio'],
                    'subtotal' => $subtotal,
                ]);

                $producto->decrement('stock', $productoData['cantidad']);
                $total += $subtotal;
            }

            // Actualizar venta
            $vuelto = 0;
            if ($request->metodo_pago !== 'cc' && $request->monto_recibido > $total) {
                $vuelto = $request->monto_recibido - $total;
            }

            $venta->update([
                'total' => $total,
                'vuelto' => $vuelto,
            ]);

            // Movimiento de caja
            if ($request->metodo_pago !== 'cc') {
                Caja::create([
                    'usuario_id' => auth()->id(),
                    'tipo' => 'ingreso',
                    'concepto' => "Venta #{$venta->numero}",
                    'monto' => $total,
                ]);
            } else if ($venta->cliente) {
                $venta->cliente->descontarSaldo($total);
            }

            DB::commit();

            $venta->load(['cliente', 'detalles.producto']);

            return response()->json([
                'success' => true,
                'message' => 'Venta creada exitosamente',
                'venta' => $venta,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Obtener estadísticas del dashboard
    public function estadisticas()
    {
        $stats = [
            'ventas_hoy' => [
                'cantidad' => Venta::hoy()->count(),
                'total' => Venta::hoy()->sum('total'),
            ],
            'saldo_caja' => Caja::saldoActual(),
            'productos_stock_bajo' => Producto::stockBajo()->count(),
            'clientes_deuda' => Cliente::conDeuda()->count(),
        ];

        return response()->json($stats);
    }

    // Obtener producto por código de barras
    public function productoPorCodigo(Request $request)
    {
        $codigo = $request->get('codigo');
        $listaPrecioId = $request->get('lista_precio_id', 1);

        $producto = Producto::activos()
            ->where('codigo', $codigo)
            ->with(['precios' => function ($q) use ($listaPrecioId) {
                $q->where('listas_precios.id', $listaPrecioId);
            }])
            ->first();

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'producto' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'codigo' => $producto->codigo,
                'stock' => $producto->stock,
                'precio' => $producto->precios->first()->pivot->precio ?? 0,
                'stock_bajo' => $producto->stockBajo(),
            ],
        ]);
    }

    // Validar cliente para cuenta corriente
    public function validarClienteCc($clienteId)
    {
        $cliente = Cliente::find($clienteId);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'saldo_cc' => $cliente->saldo_cc,
                'puede_comprar_cc' => $cliente->saldo_cc >= -5000, // límite de crédito
            ],
        ]);
    }

    // Cerrar turno/caja
    public function cerrarTurno(Request $request)
    {
        try {
            $request->validate([
                'monto_fisico' => 'required|numeric|min:0',
                'observaciones' => 'nullable|string|max:500',
            ]);

            $ventasDelDia = Venta::hoy()->get();
            $movimientosDelDia = Caja::hoy()->get();
            
            $saldoSistema = Caja::saldoHoy();
            $diferencia = $request->monto_fisico - $saldoSistema;

            // Registrar diferencia si existe
            if (abs($diferencia) > 0.01) { // Tolerancia de 1 centavo
                $tipo = $diferencia > 0 ? 'ingreso' : 'egreso';
                $concepto = $diferencia > 0 ? 'Sobrante de caja' : 'Faltante de caja';

                Caja::create([
                    'usuario_id' => auth()->id(),
                    'tipo' => $tipo,
                    'concepto' => $concepto . ' - Cierre de turno',
                    'monto' => abs($diferencia),
                ]);
            }

            $resumen = [
                'fecha' => now()->format('Y-m-d'),
                'usuario' => auth()->user()->nombre,
                'ventas' => [
                    'cantidad' => $ventasDelDia->count(),
                    'total' => $ventasDelDia->sum('total'),
                ],
                'caja' => [
                    'saldo_sistema' => $saldoSistema,
                    'monto_fisico' => $request->monto_fisico,
                    'diferencia' => $diferencia,
                ],
                'observaciones' => $request->observaciones,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Turno cerrado exitosamente',
                'resumen' => $resumen,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Obtener alertas del sistema
    public function alertas()
    {
        $alertas = [];

        // Stock bajo
        $stockBajo = Producto::stockBajo()->count();
        if ($stockBajo > 0) {
            $alertas[] = [
                'tipo' => 'stock_bajo',
                'mensaje' => "{$stockBajo} productos con stock bajo",
                'cantidad' => $stockBajo,
            ];
        }

        // Productos próximos a vencer
        $proximosVencer = Producto::whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(7))
            ->count();
        if ($proximosVencer > 0) {
            $alertas[] = [
                'tipo' => 'vencimiento',
                'mensaje' => "{$proximosVencer} productos vencen esta semana",
                'cantidad' => $proximosVencer,
            ];
        }

        return response()->json($alertas);
    }
}
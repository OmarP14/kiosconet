<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function ventas(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $ventas = Venta::with(['usuario', 'cliente', 'detalles.producto'])
                      ->entreFechas($fechaInicio, $fechaFin)
                      ->get();

        $resumen = [
            'total_ventas' => $ventas->sum('total'),
            'cantidad_ventas' => $ventas->count(),
            'ticket_promedio' => $ventas->avg('total'),
            'por_metodo_pago' => $ventas->groupBy('metodo_pago')->map(function ($grupo) {
                return [
                    'cantidad' => $grupo->count(),
                    'total' => $grupo->sum('total'),
                    'porcentaje' => 0, // Se calculará en la vista
                ];
            }),
            'por_vendedor' => $ventas->groupBy('usuario.nombre')->map(function ($grupo) {
                return [
                    'cantidad' => $grupo->count(),
                    'total' => $grupo->sum('total'),
                ];
            }),
            'por_dia' => $ventas->groupBy(function ($venta) {
                return $venta->created_at->format('Y-m-d');
            })->map(function ($grupo) {
                return [
                    'cantidad' => $grupo->count(),
                    'total' => $grupo->sum('total'),
                ];
            }),
        ];

        return view('reportes.ventas', compact('ventas', 'resumen', 'fechaInicio', 'fechaFin'));
    }

    public function productos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $productosVendidos = DetalleVenta::select(
                'producto_id',
                DB::raw('SUM(cantidad) as total_vendido'),
                DB::raw('SUM(subtotal) as ingresos'),
                DB::raw('AVG(precio_unitario) as precio_promedio')
            )
            ->whereHas('venta', function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            })
            ->with(['producto.proveedor', 'producto.precios'])
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->get();

        $stockActual = Producto::with(['proveedor', 'precios'])
                             ->select('*', DB::raw('(stock * precio_compra) as valor_stock'))
                             ->get();

        $resumen = [
            'productos_vendidos' => $productosVendidos->count(),
            'total_unidades_vendidas' => $productosVendidos->sum('total_vendido'),
            'ingresos_totales' => $productosVendidos->sum('ingresos'),
            'valor_stock_actual' => $stockActual->sum('valor_stock'),
            'productos_sin_movimiento' => Producto::whereNotIn('id', $productosVendidos->pluck('producto_id'))->count(),
        ];

        return view('reportes.productos', compact('productosVendidos', 'stockActual', 'resumen', 'fechaInicio', 'fechaFin'));
    }

    public function clientes(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $clientesVentas = Cliente::select('clientes.*')
            ->selectSub(function ($query) use ($fechaInicio, $fechaFin) {
                $query->selectRaw('COUNT(*)')
                      ->from('ventas')
                      ->whereColumn('ventas.cliente_id', 'clientes.id')
                      ->whereBetween('ventas.created_at', [$fechaInicio, $fechaFin]);
            }, 'total_compras')
            ->selectSub(function ($query) use ($fechaInicio, $fechaFin) {
                $query->selectRaw('COALESCE(SUM(total), 0)')
                      ->from('ventas')
                      ->whereColumn('ventas.cliente_id', 'clientes.id')
                      ->whereBetween('ventas.created_at', [$fechaInicio, $fechaFin]);
            }, 'total_gastado')
            ->selectSub(function ($query) use ($fechaInicio, $fechaFin) {
                $query->selectRaw('MAX(created_at)')
                      ->from('ventas')
                      ->whereColumn('ventas.cliente_id', 'clientes.id')
                      ->whereBetween('ventas.created_at', [$fechaInicio, $fechaFin]);
            }, 'ultima_compra')
            ->having('total_compras', '>', 0)
            ->orderBy('total_gastado', 'desc')
            ->get();

        $resumen = [
            'clientes_activos' => $clientesVentas->count(),
            'total_clientes' => Cliente::count(),
            'ingresos_por_clientes' => $clientesVentas->sum('total_gastado'),
            'compra_promedio' => $clientesVentas->avg('total_gastado'),
            'clientes_con_deuda' => Cliente::conDeuda()->count(),
            'total_deuda' => Cliente::conDeuda()->sum('saldo_cc'),
            'clientes_con_saldo' => Cliente::conSaldo()->count(),
            'total_saldo_favor' => Cliente::conSaldo()->sum('saldo_cc'),
        ];

        return view('reportes.clientes', compact('clientesVentas', 'resumen', 'fechaInicio', 'fechaFin'));
    }

    public function caja(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $movimientos = Caja::with('usuario')
                          ->entreFechas($fechaInicio, $fechaFin)
                          ->get();

        $ingresosPorConcepto = $movimientos->where('tipo', 'ingreso')
                                         ->groupBy('concepto')
                                         ->map->sum('monto')
                                         ->sortDesc();

        $egresosPorConcepto = $movimientos->where('tipo', 'egreso')
                                        ->groupBy('concepto')
                                        ->map->sum('monto')
                                        ->sortDesc();

        $movimientosPorDia = $movimientos->groupBy(function ($movimiento) {
            return $movimiento->created_at->format('Y-m-d');
        })->map(function ($grupo) {
            return [
                'ingresos' => $grupo->where('tipo', 'ingreso')->sum('monto'),
                'egresos' => $grupo->where('tipo', 'egreso')->sum('monto'),
                'saldo' => $grupo->where('tipo', 'ingreso')->sum('monto') - $grupo->where('tipo', 'egreso')->sum('monto'),
            ];
        });

        $resumen = [
            'total_ingresos' => $movimientos->where('tipo', 'ingreso')->sum('monto'),
            'total_egresos' => $movimientos->where('tipo', 'egreso')->sum('monto'),
            'saldo_periodo' => $movimientos->where('tipo', 'ingreso')->sum('monto') - $movimientos->where('tipo', 'egreso')->sum('monto'),
            'cantidad_ingresos' => $movimientos->where('tipo', 'ingreso')->count(),
            'cantidad_egresos' => $movimientos->where('tipo', 'egreso')->count(),
            'promedio_ingreso' => $movimientos->where('tipo', 'ingreso')->avg('monto'),
            'promedio_egreso' => $movimientos->where('tipo', 'egreso')->avg('monto'),
            'saldo_actual' => Caja::saldoActual(),
        ];

        return view('reportes.caja', compact('movimientos', 'resumen', 'ingresosPorConcepto', 'egresosPorConcepto', 'movimientosPorDia', 'fechaInicio', 'fechaFin'));
    }

    public function rentabilidad(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $productosRentabilidad = DetalleVenta::select(
                'producto_id',
                DB::raw('SUM(cantidad) as unidades_vendidas'),
                DB::raw('SUM(subtotal) as ingresos'),
                DB::raw('SUM(cantidad * productos.precio_compra) as costo_mercaderia')
            )
            ->join('productos', 'detalle_venta.producto_id', '=', 'productos.id')
            ->whereHas('venta', function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            })
            ->with('producto')
            ->groupBy('producto_id', 'productos.precio_compra')
            ->get()
            ->map(function ($item) {
                $item->margen_bruto = $item->ingresos - $item->costo_mercaderia;
                $item->porcentaje_margen = $item->costo_mercaderia > 0 ? (($item->margen_bruto / $item->costo_mercaderia) * 100) : 0;
                return $item;
            })
            ->sortByDesc('margen_bruto');

        $resumen = [
            'ingresos_totales' => $productosRentabilidad->sum('ingresos'),
            'costos_totales' => $productosRentabilidad->sum('costo_mercaderia'),
            'margen_bruto_total' => $productosRentabilidad->sum('margen_bruto'),
            'porcentaje_margen_promedio' => $productosRentabilidad->avg('porcentaje_margen'),
            'productos_analizados' => $productosRentabilidad->count(),
        ];

        return view('reportes.rentabilidad', compact('productosRentabilidad', 'resumen', 'fechaInicio', 'fechaFin'));
    }

    public function exportarVentas(Request $request)
    {
        // Aquí implementarías la exportación a CSV o PDF
        // Por ahora devuelvo una respuesta JSON con los datos
        
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $ventas = Venta::with(['usuario', 'cliente', 'detalles.producto'])
                      ->entreFechas($fechaInicio, $fechaFin)
                      ->get();

        return response()->json($ventas);
    }
    /**
     * Obtener datos de ventas por método de pago
     */
    public function ventasPorMetodoPago(Request $request)
    {
        // Obtener las fechas del filtro
        $periodo = $request->get('periodo', 'hoy');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        // Si no son fechas personalizadas, usar el período
        if (!$fechaInicio || !$fechaFin) {
            switch ($periodo) {
                case 'hoy':
                    $fechaInicio = now()->startOfDay();
                    $fechaFin = now()->endOfDay();
                    break;
                case 'ayer':
                    $fechaInicio = now()->yesterday()->startOfDay();
                    $fechaFin = now()->yesterday()->endOfDay();
                    break;
                case 'semana':
                    $fechaInicio = now()->startOfWeek();
                    $fechaFin = now()->endOfWeek();
                    break;
                case 'mes':
                    $fechaInicio = now()->startOfMonth();
                    $fechaFin = now()->endOfMonth();
                    break;
                case 'año':
                    $fechaInicio = now()->startOfYear();
                    $fechaFin = now()->endOfYear();
                    break;
                default:
                    $fechaInicio = now()->startOfDay();
                    $fechaFin = now()->endOfDay();
            }
        }

        // Buscar las ventas que NO están anuladas
        $ventas = Venta::where('anulada', false)
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->select(
                'metodo_pago',
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('metodo_pago')
            ->get();

        // Calcular el total general
        $totalGeneral = $ventas->sum('total');
        $cantidadGeneral = $ventas->sum('cantidad');

        // Preparar los datos para mostrar
        $datos = $ventas->map(function($venta) use ($totalGeneral) {
            $porcentaje = $totalGeneral > 0 ? ($venta->total / $totalGeneral) * 100 : 0;
            
            return [
                'metodo' => $this->getNombreMetodoPago($venta->metodo_pago),
                'metodo_raw' => $venta->metodo_pago,
                'cantidad' => $venta->cantidad,
                'total' => $venta->total,
                'total_formateado' => '$' . number_format($venta->total, 2),
                'porcentaje' => round($porcentaje, 2),
                'color' => $this->getColorMetodoPago($venta->metodo_pago),
                'icono' => $this->getIconoMetodoPago($venta->metodo_pago),
            ];
        });

        return response()->json([
            'success' => true,
            'datos' => $datos,
            'total_general' => $totalGeneral,
            'total_general_formateado' => '$' . number_format($totalGeneral, 2),
            'cantidad_general' => $cantidadGeneral,
            'fecha_inicio' => date('d/m/Y', strtotime($fechaInicio)),
            'fecha_fin' => date('d/m/Y', strtotime($fechaFin)),
        ]);
    }

    /**
     * Obtener resumen de ventas por período
     */
    public function resumenVentas(Request $request)
    {
        $periodo = $request->get('periodo', 'hoy');
        
        // Definir las fechas según el período
        switch ($periodo) {
            case 'hoy':
                $fechaInicio = now()->startOfDay();
                $fechaFin = now()->endOfDay();
                break;
            case 'ayer':
                $fechaInicio = now()->yesterday()->startOfDay();
                $fechaFin = now()->yesterday()->endOfDay();
                break;
            case 'semana':
                $fechaInicio = now()->startOfWeek();
                $fechaFin = now()->endOfWeek();
                break;
            case 'mes':
                $fechaInicio = now()->startOfMonth();
                $fechaFin = now()->endOfMonth();
                break;
            case 'año':
                $fechaInicio = now()->startOfYear();
                $fechaFin = now()->endOfYear();
                break;
            default:
                $fechaInicio = now()->startOfDay();
                $fechaFin = now()->endOfDay();
        }

        // Obtener estadísticas
        $ventas = Venta::where('anulada', false)
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->get();

        $totalVentas = $ventas->sum('total');
        $cantidadVentas = $ventas->count();
        $promedioVenta = $cantidadVentas > 0 ? $totalVentas / $cantidadVentas : 0;

        return response()->json([
            'success' => true,
            'periodo' => $periodo,
            'fecha_inicio' => $fechaInicio->format('d/m/Y'),
            'fecha_fin' => $fechaFin->format('d/m/Y'),
            'total_ventas' => $totalVentas,
            'total_ventas_formateado' => '$' . number_format($totalVentas, 2),
            'cantidad_ventas' => $cantidadVentas,
            'promedio_venta' => $promedioVenta,
            'promedio_venta_formateado' => '$' . number_format($promedioVenta, 2),
        ]);
    }

    /**
     * Obtener nombre bonito del método de pago
     */
    private function getNombreMetodoPago($metodo)
    {
        $nombres = [
            'efectivo' => 'Efectivo',
            'tarjeta' => 'Tarjeta',
            'transferencia' => 'Transferencia',
            'cuenta_corriente' => 'Cuenta Corriente',
            'cc' => 'Cuenta Corriente',
            'mixto' => 'Pago Mixto',
            'billetera' => 'Billetera Digital',
        ];

        return $nombres[$metodo] ?? ucfirst($metodo);
    }

    /**
     * Obtener color para cada método de pago
     */
    private function getColorMetodoPago($metodo)
    {
        $colores = [
            'efectivo' => '#28a745',      // Verde
            'tarjeta' => '#007bff',       // Azul
            'transferencia' => '#ffc107', // Amarillo
            'cuenta_corriente' => '#6f42c1', // Morado
            'cc' => '#6f42c1',            // Morado
            'mixto' => '#fd7e14',         // Naranja
            'billetera' => '#17a2b8',     // Cyan
        ];

        return $colores[$metodo] ?? '#6c757d';
    }

    /**
     * Obtener ícono para cada método de pago
     */
    private function getIconoMetodoPago($metodo)
    {
        $iconos = [
            'efectivo' => 'fa-money-bill-wave',
            'tarjeta' => 'fa-credit-card',
            'transferencia' => 'fa-exchange-alt',
            'cuenta_corriente' => 'fa-file-invoice-dollar',
            'cc' => 'fa-file-invoice-dollar',
            'mixto' => 'fa-coins',
            'billetera' => 'fa-wallet',
        ];

        return $iconos[$metodo] ?? 'fa-shopping-cart';
    }
}
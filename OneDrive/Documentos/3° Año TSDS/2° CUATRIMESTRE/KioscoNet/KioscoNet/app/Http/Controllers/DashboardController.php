<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // ==========================================
            // ESTADÍSTICAS PARA LAS TARJETAS (Array $stats)
            // ==========================================
            $stats = [
                'ventas_hoy' => $this->getVentasHoy(),
                'ingresos_hoy' => $this->getIngresosHoy(),
                'productos_stock_bajo' => $this->getConteoStockBajo(),
                'saldo_caja' => $this->getSaldoCaja(),
            ];

            // ==========================================
            // PRODUCTOS CON STOCK BAJO (Colección - Lista)
            // ==========================================
            $productosStockBajo = Producto::whereRaw('stock <= stock_minimo')
                ->orWhere('stock', '<=', 5)
                ->with('proveedor')
                ->orderBy('stock', 'asc')
                ->limit(10)
                ->get();

            // ==========================================
            // PRODUCTOS PRÓXIMOS A VENCER (Colección - Lista)
            // ==========================================
            $productosProximosVencer = Producto::whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<=', Carbon::now()->addDays(30))
                ->where('fecha_vencimiento', '>=', Carbon::now())
                ->with('proveedor')
                ->orderBy('fecha_vencimiento', 'asc')
                ->limit(10)
                ->get();

            // ==========================================
            // PRODUCTOS VENCIDOS
            // ==========================================
            $productosVencidos = Producto::whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<', Carbon::now())
                ->with('proveedor')
                ->orderBy('fecha_vencimiento', 'desc')
                ->limit(5)
                ->get();

            // ==========================================
            // VENTAS RECIENTES (Colección - Últimas 10)
            // ==========================================
            $ventasRecientes = Venta::where('anulada', false)
                ->with('cliente')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // ==========================================
            // DATOS PARA GRÁFICO: VENTAS SEMANALES
            // ==========================================
            $ventasSemanales = $this->getVentasSemanales();

            // ==========================================
            // DATOS PARA GRÁFICO: PRODUCTOS MÁS VENDIDOS
            // ==========================================
            $productosMasVendidos = $this->getProductosMasVendidos();

            // ==========================================
            // ENVIAR TODO A LA VISTA
            // ==========================================
            return view('dashboard', compact(
                'stats',
                'productosStockBajo',
                'productosProximosVencer',
                'productosVencidos',
                'ventasRecientes',
                'ventasSemanales',
                'productosMasVendidos'
            ));

        } catch (\Exception $e) {
            // Si hay error, valores por defecto seguros
            $stats = [
                'ventas_hoy' => 0,
                'ingresos_hoy' => 0,
                'productos_stock_bajo' => 0,
                'saldo_caja' => 0,
            ];

            $productosStockBajo = collect();
            $productosProximosVencer = collect();
            $productosVencidos = collect();
            $ventasRecientes = collect();
            $ventasSemanales = ['labels' => [], 'data' => []];
            $productosMasVendidos = ['labels' => [], 'data' => []];

            return view('dashboard', compact(
                'stats',
                'productosStockBajo',
                'productosProximosVencer',
                'productosVencidos',
                'ventasRecientes',
                'ventasSemanales',
                'productosMasVendidos'
            ))->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }

    // ==========================================
    // MÉTODOS PRIVADOS - ESTADÍSTICAS
    // ==========================================

    /**
     * Obtiene la cantidad de ventas de hoy
     */
    private function getVentasHoy()
    {
        try {
            return Venta::where('anulada', false)
                ->whereDate('fecha_venta', Carbon::today())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene el total de ingresos de hoy
     */
    private function getIngresosHoy()
    {
        try {
            return Venta::where('anulada', false)
                ->whereDate('fecha_venta', Carbon::today())
                ->sum('total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Cuenta cuántos productos tienen stock bajo
     */
    private function getConteoStockBajo()
    {
        try {
            return Producto::whereRaw('stock <= stock_minimo')
                ->orWhere('stock', '<=', 5)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene el saldo actual de caja
     */
    private function getSaldoCaja()
    {
        try {
            $ultimaCaja = Caja::orderBy('created_at', 'desc')->first();
            return $ultimaCaja ? $ultimaCaja->saldo_actual : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // ==========================================
    // MÉTODOS PRIVADOS - DATOS PARA GRÁFICOS
    // ==========================================

    /**
     * Prepara datos para el gráfico de ventas semanales
     * Retorna: ['labels' => [...], 'data' => [...]]
     */
    private function getVentasSemanales()
    {
        try {
            $labels = [];
            $data = [];
            $diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

            // Obtener ventas de los últimos 7 días
            for ($i = 6; $i >= 0; $i--) {
                $fecha = Carbon::today()->subDays($i);
                $diaSemana = $diasSemana[$fecha->dayOfWeek];
                
                $totalVentas = Venta::where('anulada', false)
                    ->whereDate('fecha_venta', $fecha)
                    ->sum('total');
                
                $labels[] = $diaSemana;
                $data[] = round($totalVentas, 2);
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];

        } catch (\Exception $e) {
            // Si hay error, retornar estructura vacía
            return [
                'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                'data' => [0, 0, 0, 0, 0, 0, 0]
            ];
        }
    }

    /**
     * Prepara datos para el gráfico de productos más vendidos
     * Retorna: ['labels' => [...], 'data' => [...]]
     */
    private function getProductosMasVendidos()
    {
        try {
            // Verificar si la tabla detalle_venta existe
            if (!DB::getSchemaBuilder()->hasTable('detalle_venta')) {
                return [
                    'labels' => ['Sin datos'],
                    'data' => [0]
                ];
            }

            // Obtener top 5 productos más vendidos del último mes
            $productos = DetalleVenta::select(
                    'productos.nombre',
                    DB::raw('SUM(detalle_venta.cantidad) as total_vendido')
                )
                ->join('productos', 'detalle_venta.producto_id', '=', 'productos.id')
                ->join('ventas', 'detalle_venta.venta_id', '=', 'ventas.id')
                ->where('ventas.anulada', false)
                ->where('ventas.fecha_venta', '>=', Carbon::now()->subMonth())
                ->groupBy('productos.id', 'productos.nombre')
                ->orderBy('total_vendido', 'desc')
                ->limit(5)
                ->get();

            // Separar en labels y data para el gráfico
            $labels = [];
            $data = [];

            foreach ($productos as $producto) {
                $labels[] = $producto->nombre;
                $data[] = $producto->total_vendido;
            }

            // Si no hay datos, retornar estructura por defecto
            if (empty($labels)) {
                return [
                    'labels' => ['Sin datos'],
                    'data' => [0]
                ];
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];

        } catch (\Exception $e) {
            // Si hay error, retornar estructura vacía
            return [
                'labels' => ['Sin datos'],
                'data' => [0]
            ];
        }
    }

    // ==========================================
    // MÉTODOS ADICIONALES (Opcionales)
    // ==========================================

    /**
     * Obtiene estadísticas adicionales (para futuras ampliaciones)
     */
    private function getEstadisticasAdicionales()
    {
        try {
            return [
                'ventas_mes_actual' => Venta::where('anulada', false)
                    ->whereMonth('fecha_venta', Carbon::now()->month)
                    ->whereYear('fecha_venta', Carbon::now()->year)
                    ->sum('total'),
                
                'ventas_mes_anterior' => Venta::where('anulada', false)
                    ->whereMonth('fecha_venta', Carbon::now()->subMonth()->month)
                    ->whereYear('fecha_venta', Carbon::now()->subMonth()->year)
                    ->sum('total'),
                
                'clientes_nuevos_mes' => Cliente::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count(),
            ];
        } catch (\Exception $e) {
            return [
                'ventas_mes_actual' => 0,
                'ventas_mes_anterior' => 0,
                'clientes_nuevos_mes' => 0,
            ];
        }
    }
}
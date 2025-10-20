{{-- 
    =====================================================
    DASHBOARD PRINCIPAL - KIOSCONET
    Sistema de Gestión para Kioscos
    =====================================================
--}}

@extends('layouts.app')

@section('title', 'Dashboard - Sistema Kiosco')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">
                    <i class="fas fa-tachometer-alt text-primary"></i> Dashboard
                </h1>
                <p class="text-muted mb-0">
                    Bienvenido, <strong>{{ auth()->user()->nombre }}</strong> 
                    <span class="badge bg-{{ auth()->user()->esAdministrador() ? 'danger' : 'info' }}">
                        {{ ucfirst(auth()->user()->rol) }}
                    </span>
                </p>
            </div>
            <div>
                <span class="text-muted">
                    <i class="far fa-calendar-alt"></i> {{ now()->format('d/m/Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-primary text-uppercase fw-bold small mb-1">Ventas Hoy</div>
                        <div class="h4 mb-0 fw-bold text-gray-800">{{ $stats['ventas_hoy'] ?? 0 }}</div>
                        <small class="text-muted"><i class="fas fa-arrow-up text-success"></i> Transacciones</small>
                    </div>
                    <div><i class="fas fa-shopping-cart fa-2x text-primary opacity-25"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('ventas.index') }}" class="text-primary text-decoration-none small">
                    Ver todas las ventas <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-success text-uppercase fw-bold small mb-1">Ingresos Hoy</div>
                        <div class="h4 mb-0 fw-bold text-gray-800">${{ number_format($stats['ingresos_hoy'] ?? 0, 2) }}</div>
                        <small class="text-muted"><i class="fas fa-dollar-sign text-success"></i> En efectivo y tarjeta</small>
                    </div>
                    <div><i class="fas fa-money-bill-wave fa-2x text-success opacity-25"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('caja.index') }}" class="text-success text-decoration-none small">
                    Ver movimientos de caja <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-warning text-uppercase fw-bold small mb-1">Stock Bajo</div>
                        <div class="h4 mb-0 fw-bold text-gray-800">{{ $stats['productos_stock_bajo'] ?? 0 }}</div>
                        <small class="text-muted"><i class="fas fa-exclamation-triangle text-warning"></i> Productos críticos</small>
                    </div>
                    <div><i class="fas fa-boxes fa-2x text-warning opacity-25"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('productos.index') }}" class="text-warning text-decoration-none small">
                    Ver productos <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm border-start border-info border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-info text-uppercase fw-bold small mb-1">Saldo Caja</div>
                        <div class="h4 mb-0 fw-bold text-gray-800">${{ number_format($stats['saldo_caja'] ?? 0, 2) }}</div>
                        <small class="text-muted"><i class="fas fa-wallet text-info"></i> Disponible</small>
                    </div>
                    <div><i class="fas fa-cash-register fa-2x text-info opacity-25"></i></div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('caja.arqueo') }}" class="text-info text-decoration-none small">
                    Hacer arqueo <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 mb-3">
        <h5 class="fw-bold"><i class="fas fa-bolt text-warning"></i> Accesos Rápidos</h5>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('ventas.create') }}" class="btn btn-primary w-100 shadow-sm" style="min-height: 100px;">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="fas fa-plus-circle fa-3x mb-2"></i>
                <span class="fw-bold">Nueva Venta</span>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('productos.index') }}" class="btn btn-success w-100 shadow-sm" style="min-height: 100px;">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="fas fa-box fa-3x mb-2"></i>
                <span class="fw-bold">Productos</span>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('clientes.index') }}" class="btn btn-info w-100 shadow-sm" style="min-height: 100px;">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="fas fa-users fa-3x mb-2"></i>
                <span class="fw-bold">Clientes</span>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('caja.index') }}" class="btn btn-warning w-100 shadow-sm" style="min-height: 100px;">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="fas fa-cash-register fa-3x mb-2"></i>
                <span class="fw-bold">Caja</span>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('caja.arqueo') }}" class="btn btn-secondary w-100 shadow-sm" style="min-height: 100px;">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="fas fa-calculator fa-3x mb-2"></i>
                <span class="fw-bold">Arqueo</span>
            </div>
        </a>
    </div>

    @if(optional(auth()->user())->esAdministrador())
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('reportes.index') }}" class="btn btn-dark w-100 shadow-sm" style="min-height: 100px;">
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="fas fa-chart-bar fa-3x mb-2"></i>
                <span class="fw-bold">Reportes</span>
            </div>
        </a>
    </div>
    @endif
</div>

<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-area me-2"></i>Ventas de los Últimos 7 Días
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="position: relative; height: 300px;">
                    <canvas id="ventasSemanales"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-success">
                    <i class="fas fa-chart-bar me-2"></i>Top Productos
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-bar" style="position: relative; height: 300px;">
                    <canvas id="productosMasVendidos"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($productosStockBajo) && is_object($productosStockBajo) && $productosStockBajo->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm border-start border-warning border-4">
            <div class="card-header bg-warning bg-opacity-10 py-3">
                <h6 class="m-0 fw-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Productos con Stock Bajo ({{ $productosStockBajo->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Producto</th>
                                <th class="border-0">Stock Actual</th>
                                <th class="border-0">Stock Mínimo</th>
                                <th class="border-0">Proveedor</th>
                                <th class="border-0">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productosStockBajo as $producto)
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-box text-muted me-2"></i>
                                    {{ $producto->nombre }}
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $producto->stock }} unidades</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $producto->stock_minimo ?? 0 }}</span>
                                </td>
                                <td>{{ $producto->proveedor->nombre ?? 'Sin proveedor' }}</td>
                                <td>
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-outline-primary" title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3">
                <a href="{{ route('productos.index') }}" class="btn btn-warning">
                    <i class="fas fa-boxes me-2"></i>Ver todos los productos
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($ventasRecientes) && is_object($ventasRecientes) && $ventasRecientes->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-clock me-2"></i>Ventas Recientes
                </h6>
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">#ID</th>
                                <th class="border-0">Cliente</th>
                                <th class="border-0">Fecha</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Estado</th>
                                <th class="border-0">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventasRecientes as $venta)
                            <tr>
                                <td class="fw-bold">#{{ $venta->id }}</td>
                                <td>
                                    <i class="fas fa-user text-muted me-2"></i>
                                    {{ $venta->cliente->nombre ?? 'Cliente de contado' }}
                                </td>
                                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-bold text-success">${{ number_format($venta->total, 2) }}</td>
                                <td>
                                    @if($venta->anulada)
                                        <span class="badge bg-danger">Anulada</span>
                                    @else
                                        <span class="badge bg-success">Completada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .border-start { border-left-width: 4px !important; }
    .btn:hover { transform: translateY(-2px); transition: transform 0.2s ease-in-out; }
    .opacity-25 { opacity: 0.25; }
    .card { transition: box-shadow 0.3s ease-in-out; }
    .card:hover { box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Gráfico de Ventas Semanales
    const ventasCtx = document.getElementById('ventasSemanales');
    if (ventasCtx) {
        const ventasData = {
            labels: @json($ventasSemanales['labels'] ?? []),
            values: @json($ventasSemanales['data'] ?? [])
        };

        // Valores por defecto si están vacíos
        if (ventasData.labels.length === 0) {
            ventasData.labels = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
            ventasData.values = [0, 0, 0, 0, 0, 0, 0];
        }

        new Chart(ventasCtx, {
            type: 'line',
            data: {
                labels: ventasData.labels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: ventasData.values,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Total: $' + context.parsed.y.toLocaleString('es-AR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-AR');
                            }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Gráfico de Productos Más Vendidos
    const productosCtx = document.getElementById('productosMasVendidos');
    if (productosCtx) {
        const productosData = {
            labels: @json($productosMasVendidos['labels'] ?? []),
            values: @json($productosMasVendidos['data'] ?? [])
        };

        // Valores por defecto si están vacíos
        if (productosData.labels.length === 0) {
            productosData.labels = ['Sin datos'];
            productosData.values = [0];
        }

        new Chart(productosCtx, {
            type: 'bar',
            data: {
                labels: productosData.labels,
                datasets: [{
                    label: 'Unidades',
                    data: productosData.values,
                    backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                    borderRadius: 5,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Vendidos: ' + context.parsed.y + ' unidades';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10,
                            callback: function(value) {
                                return Math.floor(value);
                            }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }
    
    console.log('✅ Dashboard cargado correctamente');
});
</script>
@endpush
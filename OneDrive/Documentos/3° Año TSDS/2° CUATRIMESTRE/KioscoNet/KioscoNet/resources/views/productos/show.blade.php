@extends('layouts.app')

@section('title', 'Detalle del Producto')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-box-open text-info"></i>
                        Detalle del Producto
                    </h2>
                    <p class="text-muted mb-0">Información completa del producto</p>
                </div>
                <div>
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- COLUMNA IZQUIERDA: INFORMACIÓN PRINCIPAL --}}
        <div class="col-md-8">
            {{-- DATOS BÁSICOS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $producto->nombre }}</h3>
                            
                            <dl class="row">
                                <dt class="col-sm-4">
                                    <i class="fas fa-barcode me-2 text-muted"></i>Código:
                                </dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-dark">{{ $producto->codigo ?? 'Sin código' }}</span>
                                </dd>

                                <dt class="col-sm-4">
                                    <i class="fas fa-folder me-2 text-muted"></i>Categoría:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($producto->categoria)
                                        <span class="badge bg-info">{{ $producto->categoria }}</span>
                                    @else
                                        <span class="text-muted">Sin categoría</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">
                                    <i class="fas fa-truck me-2 text-muted"></i>Proveedor:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($producto->proveedor)
                                        <a href="{{ route('proveedores.show', $producto->proveedor) }}" class="text-decoration-none">
                                            {{ $producto->proveedor->nombre }}
                                        </a>
                                    @else
                                        <span class="text-muted">Sin proveedor</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">
                                    <i class="fas fa-toggle-on me-2 text-muted"></i>Estado:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($producto->activo)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times-circle me-1"></i>Inactivo
                                        </span>
                                    @endif
                                </dd>

                                @if($producto->fecha_vencimiento)
                                <dt class="col-sm-4">
                                    <i class="fas fa-calendar-times me-2 text-muted"></i>Vencimiento:
                                </dt>
                                <dd class="col-sm-8">
                                    {{ $producto->fecha_vencimiento->format('d/m/Y') }}
                                    @php
                                        $diasRestantes = $producto->fecha_vencimiento->diffInDays(now(), false);
                                        $diasRestantes = -$diasRestantes;
                                    @endphp
                                    @if($diasRestantes <= 0)
                                        <span class="badge bg-danger">Vencido</span>
                                    @elseif($diasRestantes <= 30)
                                        <span class="badge bg-warning">Vence en {{ $diasRestantes }} días</span>
                                    @else
                                        <span class="badge bg-success">{{ $diasRestantes }} días restantes</span>
                                    @endif
                                </dd>
                                @endif
                            </dl>
                        </div>

                        <div class="col-md-4 text-center">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                     class="img-fluid rounded shadow" 
                                     alt="{{ $producto->nombre }}"
                                     style="max-height: 200px;">
                            @else
                                <div class="border rounded p-4 bg-light">
                                    <i class="fas fa-image fa-5x text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">Sin imagen</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRECIOS Y STOCK --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>Precios y Stock
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        {{-- PRECIO COMPRA --}}
                        <div class="col-md-3">
                            <div class="border-end">
                                <i class="fas fa-shopping-cart fa-2x text-primary mb-2"></i>
                                <h4 class="text-primary mb-0">${{ number_format($producto->precio_compra, 2) }}</h4>
                                <small class="text-muted">Precio Compra</small>
                            </div>
                        </div>

                        {{-- PRECIO VENTA --}}
                        <div class="col-md-3">
                            <div class="border-end">
                                <i class="fas fa-tag fa-2x text-success mb-2"></i>
                                @php
                                    $precioVenta = $producto->precio_compra * 1.40;
                                    $ganancia = $precioVenta - $producto->precio_compra;
                                @endphp
                                <h4 class="text-success mb-0">${{ number_format($precioVenta, 2) }}</h4>
                                <small class="text-muted">Precio Venta (40%)</small>
                            </div>
                        </div>

                        {{-- GANANCIA --}}
                        <div class="col-md-3">
                            <div class="border-end">
                                <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                <h4 class="text-info mb-0">${{ number_format($ganancia, 2) }}</h4>
                                <small class="text-muted">Ganancia Unitaria</small>
                            </div>
                        </div>

                        {{-- STOCK --}}
                        <div class="col-md-3">
                            @php
                                $stockClass = 'text-success';
                                if ($producto->stock <= 0) {
                                    $stockClass = 'text-danger';
                                } elseif ($producto->stock <= $producto->stock_minimo) {
                                    $stockClass = 'text-warning';
                                }
                            @endphp
                            <i class="fas fa-boxes fa-2x {{ $stockClass }} mb-2"></i>
                            <h4 class="{{ $stockClass }} mb-0">{{ $producto->stock }}</h4>
                            <small class="text-muted">Stock Actual</small>
                            @if($producto->stock_minimo > 0)
                                <br><small class="text-muted">Mín: {{ $producto->stock_minimo }}</small>
                            @endif
                        </div>
                    </div>

                    <hr>

                    {{-- VALOR INVENTARIO --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <small class="text-muted d-block">Valor en Inventario (Costo)</small>
                                    <h5 class="mb-0">
                                        ${{ number_format($producto->precio_compra * $producto->stock, 2) }}
                                    </h5>
                                </div>
                                <i class="fas fa-warehouse fa-2x text-muted"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <small class="text-muted d-block">Ganancia Potencial</small>
                                    <h5 class="mb-0 text-success">
                                        ${{ number_format($ganancia * $producto->stock, 2) }}
                                    </h5>
                                </div>
                                <i class="fas fa-hand-holding-usd fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HISTORIAL DE VENTAS --}}
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Historial de Ventas (Últimas 10)
                    </h5>
                </div>
                <div class="card-body">
                    @if($producto->detallesVenta && $producto->detallesVenta->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Venta N°</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Precio Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producto->detallesVenta->take(10) as $detalle)
                                    <tr>
                                        <td>{{ $detalle->venta->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('ventas.show', $detalle->venta) }}" class="text-decoration-none">
                                                {{ $detalle->venta->numero }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $detalle->cantidad }}</span>
                                        </td>
                                        <td class="text-end">${{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td class="text-end fw-bold">${{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2">TOTAL VENDIDO:</th>
                                        <th class="text-center">
                                            {{ $producto->detallesVenta->sum('cantidad') }} unidades
                                        </th>
                                        <th></th>
                                        <th class="text-end text-success">
                                            ${{ number_format($producto->detallesVenta->sum('subtotal'), 2) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Sin ventas registradas</h5>
                            <p class="text-muted">Este producto aún no se ha vendido</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: ESTADÍSTICAS Y ACCIONES --}}
        <div class="col-md-4">
            {{-- ESTADÍSTICAS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Total Vendido</small>
                            <strong>{{ $producto->detallesVenta->sum('cantidad') }} unidades</strong>
                        </div>
                        <div class="progress">
                            @php
                                $totalVendido = $producto->detallesVenta->sum('cantidad');
                                $porcentaje = $producto->stock > 0 ? min(100, ($totalVendido / ($producto->stock + $totalVendido)) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $porcentaje }}%">
                                {{ number_format($porcentaje, 1) }}%
                            </div>
                        </div>
                    </div>

                    <hr>

                    <dl class="row mb-0">
                        <dt class="col-7">Ingresos Totales:</dt>
                        <dd class="col-5 text-end text-success fw-bold">
                            ${{ number_format($producto->detallesVenta->sum('subtotal'), 2) }}
                        </dd>

                        <dt class="col-7">Promedio por Venta:</dt>
                        <dd class="col-5 text-end">
                            @if($producto->detallesVenta->count() > 0)
                                {{ number_format($producto->detallesVenta->avg('cantidad'), 1) }} unidades
                            @else
                                0 unidades
                            @endif
                        </dd>

                        <dt class="col-7">Última Venta:</dt>
                        <dd class="col-5 text-end">
                            @if($producto->detallesVenta->count() > 0)
                                {{ $producto->detallesVenta->first()->venta->created_at->diffForHumans() }}
                            @else
                                Nunca
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- ALERTAS --}}
            @if($producto->stock <= 0)
            <div class="alert alert-danger">
                <i class="fas fa-times-circle me-2"></i>
                <strong>Sin Stock</strong><br>
                Este producto está agotado
            </div>
            @elseif($producto->stock <= $producto->stock_minimo)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Stock Bajo</strong><br>
                Quedan solo {{ $producto->stock }} unidades
            </div>
            @endif

            @if($producto->fecha_vencimiento && $producto->proximoVencimiento())
            <div class="alert alert-warning">
                <i class="fas fa-calendar-times me-2"></i>
                <strong>Próximo a Vencer</strong><br>
                Vence el {{ $producto->fecha_vencimiento->format('d/m/Y') }}
            </div>
            @endif

            {{-- ACCIONES RÁPIDAS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar Producto
                        </a>

                        <button type="button" 
                                class="btn btn-info" 
                                onclick="ajustarStock({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock }})">
                            <i class="fas fa-warehouse me-2"></i>Ajustar Stock
                        </button>

                        <button type="button" 
                                class="btn btn-primary" 
                                onclick="imprimirEtiqueta()">
                            <i class="fas fa-barcode me-2"></i>Imprimir Etiqueta
                        </button>

                        @if($producto->activo)
                        <form action="{{ route('productos.toggle-estado', $producto) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-toggle-off me-2"></i>Desactivar
                            </button>
                        </form>
                        @else
                        <form action="{{ route('productos.toggle-estado', $producto) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-toggle-on me-2"></i>Activar
                            </button>
                        </form>
                        @endif

                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </button>
                    </div>
                </div>
            </div>

            {{-- INFORMACIÓN DEL SISTEMA --}}
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5">ID:</dt>
                        <dd class="col-7">#{{ $producto->id }}</dd>

                        <dt class="col-5">Creado:</dt>
                        <dd class="col-7">{{ $producto->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-5">Modificado:</dt>
                        <dd class="col-7">{{ $producto->updated_at->format('d/m/Y H:i') }}</dd>

                        @if($producto->deleted_at)
                        <dt class="col-5">Eliminado:</dt>
                        <dd class="col-7 text-danger">{{ $producto->deleted_at->format('d/m/Y H:i') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL AJUSTAR STOCK --}}
<div class="modal fade" id="ajustarStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-warehouse"></i> Ajustar Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajustarStockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Producto: <strong id="nombreProducto"></strong>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label for="stock_actual" class="form-label">Stock Actual</label>
                            <input type="number" class="form-control" id="stock_actual" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="stock_nuevo" class="form-label">Nuevo Stock *</label>
                            <input type="number" class="form-control" id="stock_nuevo" name="stock" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="motivo_ajuste" class="form-label">Motivo del Ajuste</label>
                        <textarea class="form-control" id="motivo_ajuste" name="motivo" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ajustarStock(productoId, nombre, stockActual) {
    $('#nombreProducto').text(nombre);
    $('#stock_actual').val(stockActual);
    $('#stock_nuevo').val(stockActual);
    $('#ajustarStockForm').attr('action', `/productos/${productoId}/ajustar-stock`);
    $('#ajustarStockModal').modal('show');
}

function eliminarProducto(productoId, nombre) {
    if (confirm(`¿Está seguro de eliminar el producto "${nombre}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/productos/${productoId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function imprimirEtiqueta() {
    window.print();
}
</script>
@endpush

@push('styles')
<style>
.border-end {
    border-right: 1px solid #dee2e6 !important;
}

.card {
    border-radius: 15px;
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

@media print {
    body * {
        visibility: hidden;
    }
    #etiqueta-producto, #etiqueta-producto * {
        visibility: visible;
    }
    #etiqueta-producto {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>
@endpush
@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-boxes"></i> Gestión de Productos
                </h1>
                <div>
                    <a href="{{ route('productos.stock-bajo') }}" class="btn btn-warning me-2">
                        <i class="fas fa-exclamation-triangle"></i> Stock Bajo
                    </a>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filtros de búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-search"></i> Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('productos.index') }}" id="filtrosForm">
                <div class="row">
                    <div class="col-md-4">
                        <label for="buscar" class="form-label">Buscar Producto</label>
                        <input type="text" 
                               class="form-control" 
                               id="buscar" 
                               name="buscar" 
                               value="{{ request('buscar') }}"
                               placeholder="Nombre, código o categoría...">
                    </div>
                    <div class="col-md-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                                {{ $categoria }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="activo" class="form-label">Estado</label>
                        <select class="form-select" id="activo" name="activo">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="stock_estado" class="form-label">Stock</label>
                        <select class="form-select" id="stock_estado" name="stock_estado">
                            <option value="">Todos</option>
                            <option value="sin_stock" {{ request('stock_estado') === 'sin_stock' ? 'selected' : '' }}>Sin Stock</option>
                            <option value="stock_bajo" {{ request('stock_estado') === 'stock_bajo' ? 'selected' : '' }}>Stock Bajo</option>
                            <option value="con_stock" {{ request('stock_estado') === 'con_stock' ? 'selected' : '' }}>Con Stock</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- Estadísticas rápidas -->
@if($productos->count() > 0)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        {{-- ✅ USANDO ESTADÍSTICAS DEL CONTROLADOR --}}
                        <h4 class="mb-0">{{ $estadisticas['total'] }}</h4>
                        <p class="mb-0">Total Productos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $estadisticas['activos'] }}</h4>
                        <p class="mb-0">Productos Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $estadisticas['stock_bajo'] }}</h4>
                        <p class="mb-0">Stock Bajo</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $estadisticas['sin_stock'] }}</h4>
                        <p class="mb-0">Sin Stock</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

    <!-- Tabla de productos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Listado de Productos
            </h5>
        </div>
        <div class="card-body">
            @if($productos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Proveedor</th>
                            <th class="text-center">Stock</th>
                            <th class="text-end">Precio Compra</th>
                            <th class="text-end">Precio Venta</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr class="{{ !$producto->activo ? 'table-secondary' : '' }}">
                            <td>
                                <div>
                                    <strong>{{ $producto->nombre }}</strong>
                                    @if($producto->codigo)
                                        <br><small class="text-muted">{{ $producto->codigo }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($producto->categoria)
                                    <span class="badge bg-info">{{ $producto->categoria }}</span>
                                @else
                                    <span class="text-muted">Sin categoría</span>
                                @endif
                            </td>
                            <td>
                                @if($producto->proveedor)
                                    {{ $producto->proveedor->nombre }}
                                @else
                                    <span class="text-muted">Sin proveedor</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $stockClass = 'text-success';
                                    $stockIcon = 'fas fa-check-circle';
                                    if ($producto->stock <= 0) {
                                        $stockClass = 'text-danger';
                                        $stockIcon = 'fas fa-times-circle';
                                    } elseif ($producto->stock <= $producto->stock_minimo) {
                                        $stockClass = 'text-warning';
                                        $stockIcon = 'fas fa-exclamation-triangle';
                                    }
                                @endphp
                                <span class="{{ $stockClass }}">
                                    <i class="{{ $stockIcon }}"></i>
                                    {{ $producto->stock }}
                                </span>
                                @if($producto->stock_minimo > 0)
                                    <br><small class="text-muted">Min: {{ $producto->stock_minimo }}</small>
                                @endif
                            </td>
                            <td class="text-end">
                                ${{ number_format($producto->precio_compra, 2) }}
                            </td>
                            <td class="text-end">
                                {{-- ✅ CORREGIDO: Usar getPrecioVenta() correctamente --}}
                                @php
                                    $precioVenta = $producto->getPrecioVenta(40);
                                    $ganancia = $precioVenta - $producto->precio_compra;
                                    $margenPorcentaje = $producto->precio_compra > 0 
                                        ? (($precioVenta - $producto->precio_compra) / $producto->precio_compra) * 100 
                                        : 0;
                                @endphp
                                <strong>${{ number_format($precioVenta, 2) }}</strong>
                                @if($producto->precio_compra > 0)
                                    <br><small class="text-muted">{{ number_format($margenPorcentaje, 1) }}%</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($producto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                                
                                @if($producto->fecha_vencimiento && $producto->proximoVencimiento())
                                    <br><span class="badge bg-warning">Prox. Vencimiento</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('productos.show', $producto) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            title="Ajustar stock"
                                            onclick="ajustarStock({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock }})">
                                        <i class="fas fa-warehouse"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar"
                                            onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted">
                        Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} 
                        de {{ $productos->total() }} resultados
                    </small>
                </div>
                <div>
                    {{ $productos->appends(request()->query())->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No se encontraron productos</h5>
                <p class="text-muted">No hay productos registrados con los filtros aplicados.</p>
                <a href="{{ route('productos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Registrar Primer Producto
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para ajustar stock -->
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
                            <input type="number" 
                                   class="form-control" 
                                   id="stock_actual" 
                                   readonly 
                                   style="background-color: #f8f9fa;">
                        </div>
                        <div class="col-md-6">
                            <label for="stock_nuevo" class="form-label">Nuevo Stock *</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="stock_nuevo" 
                                   name="stock" 
                                   min="0" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="motivo_ajuste" class="form-label">Motivo del Ajuste</label>
                        <textarea class="form-control" 
                                  id="motivo_ajuste" 
                                  name="motivo" 
                                  rows="3" 
                                  placeholder="Ej: Inventario, Merma, Corrección, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar producto -->
<div class="modal fade" id="eliminarProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Eliminar Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eliminarProductoForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el producto <strong id="nombreProductoEliminar"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> 
                        Esta acción no se puede deshacer. El producto será eliminado permanentemente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar Producto
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
    $('#motivo_ajuste').val('');
    $('#ajustarStockForm').attr('action', `/productos/${productoId}/ajustar-stock`);
    
    const modal = new bootstrap.Modal(document.getElementById('ajustarStockModal'));
    modal.show();
}

function eliminarProducto(productoId, nombre) {
    $('#nombreProductoEliminar').text(nombre);
    $('#eliminarProductoForm').attr('action', `/productos/${productoId}`);
    
    const modal = new bootstrap.Modal(document.getElementById('eliminarProductoModal'));
    modal.show();
}

$(document).ready(function() {
    $('#stock_nuevo').on('input', function() {
        const stockActual = parseInt($('#stock_actual').val());
        const stockNuevo = parseInt($(this).val());
        
        if (stockNuevo !== stockActual && !$('#motivo_ajuste').val().trim()) {
            $('#motivo_ajuste').attr('required', true);
        } else {
            $('#motivo_ajuste').removeAttr('required');
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.badge {
    font-size: 0.75em;
}

.table-secondary {
    background-color: rgba(108, 117, 125, 0.075);
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 2px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush
@extends('layouts.app')

@section('title', 'Productos con Stock Bajo')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Productos con Stock Bajo
                    </h1>
                    <p class="text-muted mb-0">Productos que necesitan reabastecimiento urgente</p>
                </div>
                <div>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Todos los Productos
                    </a>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTA PRINCIPAL --}}
    <div class="alert alert-warning shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">¡Atención Requerida!</h5>
                <p class="mb-0">
                    Hay <strong>{{ $productos->total() }}</strong> productos con stock igual o inferior al mínimo recomendado.
                    Considera reabastecer estos productos pronto.
                </p>
            </div>
        </div>
    </div>

    {{-- TABLA DE PRODUCTOS CON STOCK BAJO --}}
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Productos con Stock Bajo
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
                            <th class="text-center">Stock Actual</th>
                            <th class="text-center">Stock Mínimo</th>
                            <th class="text-center">Diferencia</th>
                            <th class="text-end">Precio Compra</th>
                            <th class="text-center">Prioridad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        @php
                            $diferencia = $producto->stock_minimo - $producto->stock;
                            $porcentaje = $producto->stock_minimo > 0 
                                ? ($producto->stock / $producto->stock_minimo) * 100 
                                : 0;
                            
                            if ($producto->stock <= 0) {
                                $prioridadClase = 'danger';
                                $prioridadTexto = 'URGENTE';
                            } elseif ($porcentaje <= 25) {
                                $prioridadClase = 'danger';
                                $prioridadTexto = 'ALTA';
                            } elseif ($porcentaje <= 50) {
                                $prioridadClase = 'warning';
                                $prioridadTexto = 'MEDIA';
                            } else {
                                $prioridadClase = 'info';
                                $prioridadTexto = 'BAJA';
                            }
                        @endphp
                        <tr>
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
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ optional($producto->proveedor)->nombre ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $producto->stock <= 0 ? 'danger' : 'warning' }} fs-6">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary fs-6">{{ $producto->stock_minimo }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-minus me-1"></i>{{ $diferencia }}
                                </span>
                            </td>
                            <td class="text-end">
                                ${{ number_format($producto->precio_compra, 2) }}
                                <br><small class="text-muted">
                                    Total: ${{ number_format($producto->precio_compra * $diferencia, 2) }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $prioridadClase }} fs-6">
                                    {{ $prioridadTexto }}
                                </span>
                                <br>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-{{ $prioridadClase }}" 
                                         style="width: {{ min(100, $porcentaje) }}%"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-sm btn-success" 
                                            onclick="ajustarStock({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock }})"
                                            title="Ajustar stock">
                                        <i class="fas fa-plus-circle"></i> Reabastecer
                                    </button>
                                    <a href="{{ route('productos.edit', $producto) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="6" class="text-end">Inversión Total Necesaria:</th>
                            <th class="text-end text-danger fs-5">
                                @php
                                    $totalNecesario = $productos->sum(function($p) {
                                        $diferencia = $p->stock_minimo - $p->stock;
                                        return $p->precio_compra * $diferencia;
                                    });
                                @endphp
                                ${{ number_format($totalNecesario, 2) }}
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted">
                        Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} 
                        de {{ $productos->total() }} productos
                    </small>
                </div>
                <div>
                    {{ $productos->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-success">¡Excelente!</h5>
                <p class="text-muted">No hay productos con stock bajo en este momento.</p>
                <a href="{{ route('productos.index') }}" class="btn btn-primary">
                    <i class="fas fa-boxes me-2"></i>Ver Todos los Productos
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Ajustar Stock (igual que en index.blade.php) -->
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
                        <textarea class="form-control" id="motivo_ajuste" name="motivo" rows="3" 
                                  placeholder="Ej: Reabastecimiento, Compra a proveedor, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
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
    $('#motivo_ajuste').val('Reabastecimiento');
    $('#ajustarStockForm').attr('action', `/productos/${productoId}/ajustar-stock`);
    
    const modal = new bootstrap.Modal(document.getElementById('ajustarStockModal'));
    modal.show();
}
</script>
@endpush
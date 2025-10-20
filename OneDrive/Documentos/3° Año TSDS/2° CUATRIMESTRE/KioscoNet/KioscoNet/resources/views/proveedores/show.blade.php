@extends('layouts.app')

@section('title', 'Detalle del Proveedor')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-truck text-primary"></i>
                        {{ $proveedor->nombre }}
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Proveedor desde {{ $proveedor->created_at->format('d/m/Y') }}
                        ({{ $proveedor->created_at->diffForHumans() }})
                    </p>
                </div>
                <div>
                    <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- COLUMNA IZQUIERDA --}}
        <div class="col-md-8">
            {{-- INFORMACIÓN DEL PROVEEDOR --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información del Proveedor
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">
                                    <i class="fas fa-truck text-primary me-1"></i>Nombre:
                                </dt>
                                <dd class="col-sm-8">
                                    <strong>{{ $proveedor->nombre }}</strong>
                                </dd>

                                <dt class="col-sm-4">
                                    <i class="fas fa-phone text-success me-1"></i>Teléfono:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($proveedor->telefono)
                                        <a href="tel:{{ $proveedor->telefono }}" class="text-decoration-none">
                                            {{ $proveedor->telefono }}
                                        </a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $proveedor->telefono) }}" 
                                           target="_blank" 
                                           class="btn btn-success btn-sm ms-2"
                                           title="Enviar WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">No registrado</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>

                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">
                                    <i class="fas fa-envelope text-info me-1"></i>Email:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($proveedor->email)
                                        <a href="mailto:{{ $proveedor->email }}" class="text-decoration-none">
                                            {{ $proveedor->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">No registrado</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-4">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>Dirección:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($proveedor->direccion)
                                        {{ $proveedor->direccion }}
                                    @else
                                        <span class="text-muted">No registrada</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRODUCTOS DEL PROVEEDOR --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>
                        Productos del Proveedor ({{ $proveedor->productos->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($proveedor->productos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proveedor->productos as $producto)
                                <tr>
                                    <td>
                                        <strong>{{ $producto->nombre }}</strong>
                                        @if($producto->codigo_barra)
                                            <br><small class="text-muted">
                                                <i class="fas fa-barcode"></i> {{ $producto->codigo_barra }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($producto->stock <= $producto->stock_minimo)
                                            <span class="badge bg-danger">{{ $producto->stock }}</span>
                                        @elseif($producto->stock <= ($producto->stock_minimo * 2))
                                            <span class="badge bg-warning">{{ $producto->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $producto->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>${{ number_format($producto->precio, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($producto->activo)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('productos.show', $producto) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Ver producto">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">TOTAL PRODUCTOS:</th>
                                    <th class="text-end">
                                        ${{ number_format($proveedor->productos->sum('precio'), 2) }}
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sin productos asociados</h5>
                        <p class="text-muted">Este proveedor aún no tiene productos registrados</p>
                        <a href="{{ route('productos.create') }}?proveedor_id={{ $proveedor->id }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Agregar Producto
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- HISTORIAL DE ACTUALIZACIONES --}}
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Historial de Cambios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-clock bg-info text-white rounded-circle p-2"></i>
                            <div class="timeline-content">
                                <h6>Última Actualización</h6>
                                <p class="mb-0">
                                    {{ $proveedor->updated_at->format('d/m/Y H:i') }}
                                    <small class="text-muted">({{ $proveedor->updated_at->diffForHumans() }})</small>
                                </p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <i class="fas fa-plus bg-success text-white rounded-circle p-2"></i>
                            <div class="timeline-content">
                                <h6>Fecha de Registro</h6>
                                <p class="mb-0">
                                    {{ $proveedor->created_at->format('d/m/Y H:i') }}
                                    <small class="text-muted">({{ $proveedor->created_at->diffForHumans() }})</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="col-md-4">
            {{-- ESTADÍSTICAS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-truck fa-5x text-primary"></i>
                    </div>

                    <dl class="row mb-0">
                        <dt class="col-7">Total Productos:</dt>
                        <dd class="col-5 text-end">
                            <span class="badge bg-primary fs-6">
                                {{ $proveedor->productos->count() }}
                            </span>
                        </dd>

                        <dt class="col-7">Productos Activos:</dt>
                        <dd class="col-5 text-end">
                            <span class="badge bg-success">
                                {{ $proveedor->productos->where('activo', true)->count() }}
                            </span>
                        </dd>

                        <dt class="col-7">Productos Inactivos:</dt>
                        <dd class="col-5 text-end">
                            <span class="badge bg-secondary">
                                {{ $proveedor->productos->where('activo', false)->count() }}
                            </span>
                        </dd>

                        <dt class="col-7">Stock Total:</dt>
                        <dd class="col-5 text-end fw-bold">
                            {{ $proveedor->productos->sum('stock') }} unidades
                        </dd>

                        <dt class="col-7">Valor Inventario:</dt>
                        <dd class="col-5 text-end fw-bold text-success">
                            ${{ number_format($proveedor->productos->sum(function($p) { 
                                return $p->stock * $p->precio; 
                            }), 2) }}
                        </dd>
                    </dl>

                    <hr>

                    @if($proveedor->productos->count() > 0)
                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Producto más caro:</strong>
                            <br>{{ $proveedor->productos->sortByDesc('precio')->first()->nombre }}
                            (${{ number_format($proveedor->productos->sortByDesc('precio')->first()->precio, 2) }})
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ACCIONES RÁPIDAS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('proveedores.edit', $proveedor) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar Proveedor
                        </a>

                        <a href="{{ route('productos.create') }}?proveedor_id={{ $proveedor->id }}" 
                           class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Agregar Producto
                        </a>

                        @if($proveedor->email)
                        <a href="mailto:{{ $proveedor->email }}" 
                           class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>
                        @endif

                        @if($proveedor->telefono)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $proveedor->telefono) }}" 
                           target="_blank" 
                           class="btn btn-success">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        @endif

                        <hr>

                        <button type="button" 
                                class="btn btn-danger"
                                onclick="eliminarProveedor({{ $proveedor->id }}, '{{ $proveedor->nombre }}', {{ $proveedor->productos->count() }})">
                            <i class="fas fa-trash me-2"></i>Eliminar Proveedor
                        </button>
                    </div>
                </div>
            </div>

            {{-- INFORMACIÓN DEL SISTEMA --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-1"></i>Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5">ID:</dt>
                        <dd class="col-7">#{{ $proveedor->id }}</dd>

                        <dt class="col-5">Registrado:</dt>
                        <dd class="col-7">{{ $proveedor->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-5">Actualizado:</dt>
                        <dd class="col-7">{{ $proveedor->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div class="modal fade" id="eliminarProveedorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Eliminar Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eliminarProveedorForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el proveedor <strong id="nombreProveedorEliminar"></strong>?</p>
                    <div id="alertaProductos" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Advertencia:</strong> Este proveedor tiene productos asociados.
                        <br>No se podrá eliminar hasta que se reasignen o eliminen los productos.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="btnEliminar">
                        <i class="fas fa-trash me-2"></i>Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function eliminarProveedor(proveedorId, nombre, cantidadProductos) {
    document.getElementById('nombreProveedorEliminar').textContent = nombre;
    document.getElementById('eliminarProveedorForm').action = `/proveedores/${proveedorId}`;
    
    const alertaProductos = document.getElementById('alertaProductos');
    const btnEliminar = document.getElementById('btnEliminar');
    
    if (cantidadProductos > 0) {
        alertaProductos.classList.remove('d-none');
        btnEliminar.disabled = true;
        btnEliminar.classList.add('disabled');
    } else {
        alertaProductos.classList.add('d-none');
        btnEliminar.disabled = false;
        btnEliminar.classList.remove('disabled');
    }
    
    const modal = new bootstrap.Modal(document.getElementById('eliminarProveedorModal'));
    modal.show();
}
</script>
@endpush

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 20px;
    position: relative;
}

.timeline-item i {
    margin-right: 15px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.timeline-content {
    flex: 1;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.card {
    border-radius: 15px;
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}
</style>
@endpush
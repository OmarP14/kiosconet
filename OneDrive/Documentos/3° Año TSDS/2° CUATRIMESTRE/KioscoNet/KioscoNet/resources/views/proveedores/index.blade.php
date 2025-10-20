@extends('layouts.app')

@section('title', 'Gestión de Proveedores')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-truck text-primary"></i>
                        Gestión de Proveedores
                    </h2>
                    <p class="text-muted mb-0">
                        Administra tus proveedores y contactos comerciales
                    </p>
                </div>
                <div>
                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo Proveedor
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

    {{-- FILTROS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-search me-2"></i>Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('proveedores.index') }}" id="filtrosForm">
                <div class="row">
                    <div class="col-md-10">
                        <label for="buscar" class="form-label">Buscar Proveedor</label>
                        <input type="text" 
                               class="form-control" 
                               id="buscar" 
                               name="buscar" 
                               value="{{ request('buscar') }}"
                               placeholder="Nombre o email del proveedor...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ESTADÍSTICAS --}}
    @if($proveedores->total() > 0)
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $proveedores->total() }}</h4>
                            <p class="mb-0">Total Proveedores</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-truck fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $proveedores->where('productos_count', '>', 0)->count() }}</h4>
                            <p class="mb-0">Con Productos</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $proveedores->sum('productos_count') }}</h4>
                            <p class="mb-0">Productos Totales</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- TABLA DE PROVEEDORES --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Listado de Proveedores
            </h5>
        </div>
        <div class="card-body">
            @if($proveedores->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Proveedor</th>
                            <th>Contacto</th>
                            <th class="text-center">Productos</th>
                            <th class="text-center">Última Actualización</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proveedores as $proveedor)
                        <tr>
                            <td>
                                <div>
                                    <strong class="text-primary">
                                        <i class="fas fa-truck me-2"></i>{{ $proveedor->nombre }}
                                    </strong>
                                    @if($proveedor->direccion)
                                        <br><small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $proveedor->direccion }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($proveedor->telefono)
                                    <i class="fas fa-phone text-success"></i> 
                                    <a href="tel:{{ $proveedor->telefono }}" class="text-decoration-none">
                                        {{ $proveedor->telefono }}
                                    </a>
                                    <br>
                                @endif
                                @if($proveedor->email)
                                    <i class="fas fa-envelope text-info"></i> 
                                    <a href="mailto:{{ $proveedor->email }}" class="text-decoration-none">
                                        {{ $proveedor->email }}
                                    </a>
                                @endif
                                @if(!$proveedor->telefono && !$proveedor->email)
                                    <span class="text-muted">Sin contacto</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($proveedor->productos_count > 0)
                                    <span class="badge bg-success fs-6">
                                        {{ $proveedor->productos_count }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ $proveedor->updated_at->format('d/m/Y') }}
                                    <br>{{ $proveedor->updated_at->diffForHumans() }}
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('proveedores.show', $proveedor) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('proveedores.edit', $proveedor) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar"
                                            onclick="eliminarProveedor({{ $proveedor->id }}, '{{ $proveedor->nombre }}', {{ $proveedor->productos_count }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted">
                        Mostrando {{ $proveedores->firstItem() }} a {{ $proveedores->lastItem() }} 
                        de {{ $proveedores->total() }} resultados
                    </small>
                </div>
                <div>
                    {{ $proveedores->appends(request()->query())->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay proveedores registrados</h5>
                <p class="text-muted">No se encontraron proveedores con los filtros aplicados.</p>
                <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Registrar Primer Proveedor
                </a>
            </div>
            @endif
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
                        <strong>Advertencia:</strong> Este proveedor tiene <span id="cantidadProductos"></span> producto(s) asociado(s).
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
    $('#nombreProveedorEliminar').text(nombre);
    $('#eliminarProveedorForm').attr('action', `/proveedores/${proveedorId}`);
    
    if (cantidadProductos > 0) {
        $('#cantidadProductos').text(cantidadProductos);
        $('#alertaProductos').removeClass('d-none');
        $('#btnEliminar').prop('disabled', true).addClass('disabled');
    } else {
        $('#alertaProductos').addClass('d-none');
        $('#btnEliminar').prop('disabled', false).removeClass('disabled');
    }
    
    $('#eliminarProveedorModal').modal('show');
}
</script>
@endpush

@push('styles')
<style>
.opacity-50 {
    opacity: 0.5;
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

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 2px;
    }
}
</style>
@endpush
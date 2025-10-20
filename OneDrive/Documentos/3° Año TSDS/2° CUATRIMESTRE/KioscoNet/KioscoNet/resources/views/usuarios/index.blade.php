@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-users text-primary"></i>
                        Gestión de Usuarios
                    </h2>
                    <p class="text-muted mb-0">
                        Administra los usuarios del sistema
                    </p>
                </div>
                <div>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
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
                <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('usuarios.index') }}" id="filtrosForm">
                <div class="row">
                    <div class="col-md-8">
                        <label for="buscar" class="form-label">Buscar Usuario</label>
                        <input type="text" 
                               class="form-control" 
                               id="buscar" 
                               name="buscar" 
                               value="{{ request('buscar') }}"
                               placeholder="Nombre, email o usuario...">
                    </div>
                    <div class="col-md-4">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol">
                            <option value="">Todos</option>
                            <option value="administrador" {{ request('rol') === 'administrador' ? 'selected' : '' }}>
                                Administradores
                            </option>
                            <option value="vendedor" {{ request('rol') === 'vendedor' ? 'selected' : '' }}>
                                Vendedores
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ESTADÍSTICAS --}}
    @if($usuarios->total() > 0)
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $usuarios->total() }}</h4>
                            <p class="mb-0">Total Usuarios</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-50"></i>
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
                            <h4 class="mb-0">{{ $usuarios->where('rol', 'administrador')->count() }}</h4>
                            <p class="mb-0">Administradores</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-shield fa-2x opacity-50"></i>
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
                            <h4 class="mb-0">{{ $usuarios->where('rol', 'vendedor')->count() }}</h4>
                            <p class="mb-0">Vendedores</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-tag fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- TABLA DE USUARIOS --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Listado de Usuarios
            </h5>
        </div>
        <div class="card-body">
            @if($usuarios->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Usuario</th>
                            <th>Contacto</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Ventas</th>
                            <th class="text-center">Última Actividad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>
                                <div>
                                    <strong class="text-primary">
                                        <i class="fas fa-user me-2"></i>{{ $usuario->nombre }}
                                    </strong>
                                    <br><small class="text-muted">
                                        <i class="fas fa-at"></i> {{ $usuario->usuario }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-envelope text-info"></i> 
                                <a href="mailto:{{ $usuario->email }}" class="text-decoration-none">
                                    {{ $usuario->email }}
                                </a>
                            </td>
                            <td class="text-center">
                                @if($usuario->rol === 'administrador')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-user-shield"></i> Administrador
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-user-tag"></i> Vendedor
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($usuario->ventas_count > 0)
                                    <span class="badge bg-success fs-6">
                                        {{ $usuario->ventas_count }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ $usuario->updated_at->format('d/m/Y') }}
                                    <br>{{ $usuario->updated_at->diffForHumans() }}
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('usuarios.show', $usuario) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('usuarios.edit', $usuario) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(auth()->id() !== $usuario->id)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar"
                                            onclick="eliminarUsuario({{ $usuario->id }}, '{{ $usuario->nombre }}', {{ $usuario->ventas_count }}, '{{ $usuario->rol }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
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
                        Mostrando {{ $usuarios->firstItem() }} a {{ $usuarios->lastItem() }} 
                        de {{ $usuarios->total() }} resultados
                    </small>
                </div>
                <div>
                    {{ $usuarios->appends(request()->query())->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay usuarios registrados</h5>
                <p class="text-muted">No se encontraron usuarios con los filtros aplicados.</p>
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Registrar Primer Usuario
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div class="modal fade" id="eliminarUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Eliminar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eliminarUsuarioForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar al usuario <strong id="nombreUsuarioEliminar"></strong>?</p>
                    
                    <div id="alertaVentas" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Advertencia:</strong> Este usuario tiene ventas registradas.
                        <br>No se podrá eliminar.
                    </div>

                    <div id="alertaAdministrador" class="alert alert-danger d-none">
                        <i class="fas fa-shield-alt"></i>
                        <strong>Advertencia:</strong> Este es un administrador.
                        <br>Asegúrate de que haya al menos otro administrador en el sistema.
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
function eliminarUsuario(usuarioId, nombre, cantidadVentas, rol) {
    document.getElementById('nombreUsuarioEliminar').textContent = nombre;
    document.getElementById('eliminarUsuarioForm').action = `/usuarios/${usuarioId}`;
    
    const alertaVentas = document.getElementById('alertaVentas');
    const alertaAdministrador = document.getElementById('alertaAdministrador');
    const btnEliminar = document.getElementById('btnEliminar');
    
    // Resetear alertas
    alertaVentas.classList.add('d-none');
    alertaAdministrador.classList.add('d-none');
    btnEliminar.disabled = false;
    btnEliminar.classList.remove('disabled');
    
    // Verificar ventas
    if (cantidadVentas > 0) {
        alertaVentas.classList.remove('d-none');
        btnEliminar.disabled = true;
        btnEliminar.classList.add('disabled');
    }
    
    // Advertir si es administrador
    if (rol === 'administrador') {
        alertaAdministrador.classList.remove('d-none');
    }
    
    const modal = new bootstrap.Modal(document.getElementById('eliminarUsuarioModal'));
    modal.show();
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
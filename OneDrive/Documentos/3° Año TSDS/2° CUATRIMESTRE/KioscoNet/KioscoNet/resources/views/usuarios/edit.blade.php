@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-user-edit text-warning"></i>
                        Editar Usuario
                    </h2>
                    <p class="text-muted mb-0">
                        Modifica la información del usuario
                    </p>
                </div>
                <div>
                    <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye me-2"></i>Ver Detalle
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error:</strong> Por favor corrige los siguientes problemas:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- FORMULARIO --}}
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST" id="usuarioForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            {{-- COLUMNA IZQUIERDA --}}
            <div class="col-md-8">
                {{-- INFORMACIÓN PERSONAL --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- NOMBRE --}}
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>Nombre Completo *
                            </label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $usuario->nombre) }}" 
                                   required
                                   autofocus>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-1"></i>Email *
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $usuario->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- CREDENCIALES DE ACCESO --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-key me-2"></i>Credenciales de Acceso
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- USUARIO --}}
                        <div class="mb-3">
                            <label for="usuario" class="form-label fw-bold">
                                <i class="fas fa-at me-1"></i>Usuario *
                            </label>
                            <input type="text" 
                                   class="form-control @error('usuario') is-invalid @enderror" 
                                   id="usuario" 
                                   name="usuario" 
                                   value="{{ old('usuario', $usuario->usuario) }}" 
                                   required>
                            @error('usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Cambiar contraseña:</strong> Deja estos campos vacíos si no deseas cambiar la contraseña actual.
                        </div>

                        <div class="row">
                            {{-- PASSWORD --}}
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-1"></i>Nueva Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Dejar vacío para no cambiar">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>

                            {{-- CONFIRMAR PASSWORD --}}
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">
                                    <i class="fas fa-lock me-1"></i>Confirmar Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Repetir nueva contraseña">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ROL Y PERMISOS --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>Rol y Permisos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="rol" class="form-label fw-bold">
                                <i class="fas fa-shield-alt me-1"></i>Rol del Usuario *
                            </label>
                            <select class="form-select @error('rol') is-invalid @enderror" 
                                    id="rol" 
                                    name="rol" 
                                    required>
                                <option value="">Selecciona un rol...</option>
                                <option value="administrador" {{ old('rol', $usuario->rol) === 'administrador' ? 'selected' : '' }}>
                                    Administrador - Acceso total
                                </option>
                                <option value="vendedor" {{ old('rol', $usuario->rol) === 'vendedor' ? 'selected' : '' }}>
                                    Vendedor - Solo ventas
                                </option>
                            </select>
                            @error('rol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESCRIPCIÓN DEL ROL --}}
                        <div id="rolDescripcion" class="alert">
                            <strong id="rolTitulo"></strong>
                            <ul id="rolPermisos" class="mb-0 mt-2"></ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="col-md-4">
                {{-- PREVIEW --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-eye me-2"></i>Vista Previa
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-5x text-warning" id="preview_icon"></i>
                        </div>
                        
                        <h5 id="preview_nombre" class="text-warning">{{ $usuario->nombre }}</h5>
                        <p class="text-muted mb-0" id="preview_email">{{ $usuario->email }}</p>
                        <p class="text-muted mb-3" id="preview_usuario">@{{ $usuario->usuario }}</p>
                        
                        <div id="preview_rol_badge">
                            @if($usuario->rol === 'administrador')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-shield-alt me-1"></i>Administrador
                                </span>
                            @else
                                <span class="badge bg-primary fs-6">
                                    <i class="fas fa-store me-1"></i>Vendedor
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>Ver Detalle
                            </a>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- INFORMACIÓN DEL SISTEMA --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-1"></i>Información del Sistema
                        </h6>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0 small">
                            <dt class="col-5">ID:</dt>
                            <dd class="col-7">#{{ $usuario->id }}</dd>

                            <dt class="col-5">Creado:</dt>
                            <dd class="col-7">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-5">Actualizado:</dt>
                            <dd class="col-7">{{ $usuario->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>

                {{-- ESTADÍSTICAS RÁPIDAS --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-1"></i>Estadísticas
                        </h6>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0 small">
                            <dt class="col-7">Ventas Registradas:</dt>
                            <dd class="col-5 text-end">
                                <span class="badge bg-success">{{ $usuario->ventas->count() }}</span>
                            </dd>

                            <dt class="col-7">Movimientos Caja:</dt>
                            <dd class="col-5 text-end">
                                <span class="badge bg-info">{{ $usuario->movimientosCaja->count() }}</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // ACTUALIZAR PREVIEW EN TIEMPO REAL
    // ==========================================
    function actualizarPreview() {
        const nombre = document.getElementById('nombre').value || 'Nombre del Usuario';
        const email = document.getElementById('email').value || 'email@ejemplo.com';
        const usuario = document.getElementById('usuario').value || 'usuario';
        const rol = document.getElementById('rol').value;
        
        document.getElementById('preview_nombre').textContent = nombre;
        document.getElementById('preview_email').textContent = email;
        document.getElementById('preview_usuario').textContent = '@' + usuario;
        
        // Actualizar ícono según rol
        const icon = document.getElementById('preview_icon');
        const badgeDiv = document.getElementById('preview_rol_badge');
        
        if (rol === 'administrador') {
            icon.className = 'fas fa-user-shield fa-5x text-danger';
            badgeDiv.innerHTML = '<span class="badge bg-danger fs-6"><i class="fas fa-shield-alt me-1"></i>Administrador</span>';
        } else if (rol === 'vendedor') {
            icon.className = 'fas fa-user-tag fa-5x text-primary';
            badgeDiv.innerHTML = '<span class="badge bg-primary fs-6"><i class="fas fa-store me-1"></i>Vendedor</span>';
        }
    }
    
    // ==========================================
    // MOSTRAR DESCRIPCIÓN DEL ROL
    // ==========================================
    function mostrarDescripcionRol() {
        const rol = document.getElementById('rol').value;
        const descripcion = document.getElementById('rolDescripcion');
        const titulo = document.getElementById('rolTitulo');
        const permisos = document.getElementById('rolPermisos');
        
        if (rol === 'administrador') {
            descripcion.className = 'alert alert-danger';
            titulo.textContent = 'Administrador:';
            permisos.innerHTML = `
                <li>Acceso total al sistema</li>
                <li>Gestionar usuarios</li>
                <li>Gestionar productos y proveedores</li>
                <li>Ver todos los reportes</li>
                <li>Configurar el sistema</li>
            `;
        } else if (rol === 'vendedor') {
            descripcion.className = 'alert alert-primary';
            titulo.textContent = 'Vendedor:';
            permisos.innerHTML = `
                <li>Realizar ventas</li>
                <li>Ver productos y stock</li>
                <li>Gestionar clientes</li>
                <li>Ver reportes básicos</li>
                <li>Gestionar caja</li>
            `;
        }
    }
    
    // ==========================================
    // TOGGLE CONTRASEÑA
    // ==========================================
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
    
    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const password = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
    
    // ==========================================
    // EVENT LISTENERS
    // ==========================================
    document.getElementById('nombre').addEventListener('input', actualizarPreview);
    document.getElementById('email').addEventListener('input', actualizarPreview);
    document.getElementById('usuario').addEventListener('input', actualizarPreview);
    document.getElementById('rol').addEventListener('change', function() {
        actualizarPreview();
        mostrarDescripcionRol();
    });
    
    // Inicializar descripción del rol
    mostrarDescripcionRol();
    
    // ==========================================
    // VALIDACIÓN DEL FORMULARIO
    // ==========================================
    document.getElementById('usuarioForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        // Solo validar si se está intentando cambiar la contraseña
        if (password || passwordConfirm) {
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                document.getElementById('password_confirmation').focus();
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                document.getElementById('password').focus();
                return false;
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    border-radius: 15px;
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}
</style>
@endpush
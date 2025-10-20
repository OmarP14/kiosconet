@extends('layouts.app')

@section('title', 'Nuevo Proveedor')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-plus-circle text-primary"></i>
                        Nuevo Proveedor
                    </h2>
                    <p class="text-muted mb-0">
                        Registra un nuevo proveedor en el sistema
                    </p>
                </div>
                <div>
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
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
    <form action="{{ route('proveedores.store') }}" method="POST" id="proveedorForm">
        @csrf
        
        <div class="row">
            {{-- COLUMNA IZQUIERDA --}}
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información del Proveedor
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- NOMBRE --}}
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">
                                <i class="fas fa-truck me-1"></i>Nombre del Proveedor *
                            </label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   placeholder="Ej: Distribuidora San Juan"
                                   required
                                   autofocus>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Nombre comercial o razón social del proveedor
                            </small>
                        </div>

                        <div class="row">
                            {{-- TELÉFONO --}}
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label fw-bold">
                                    <i class="fas fa-phone me-1"></i>Teléfono
                                </label>
                                <input type="text" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="{{ old('telefono') }}" 
                                       placeholder="264-1234567">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Número de contacto principal
                                </small>
                            </div>

                            {{-- EMAIL --}}
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-1"></i>Email
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="contacto@proveedor.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Email de contacto
                                </small>
                            </div>
                        </div>

                        {{-- DIRECCIÓN --}}
                        <div class="mb-3">
                            <label for="direccion" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-1"></i>Dirección
                            </label>
                            <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                      id="direccion" 
                                      name="direccion" 
                                      rows="3" 
                                      placeholder="Av. Libertador 1234, San Juan">{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Dirección completa del proveedor
                            </small>
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
                        <i class="fas fa-truck fa-5x text-primary mb-3"></i>
                        <h5 id="preview_nombre" class="text-primary">Nombre del Proveedor</h5>
                        
                        <hr>
                        
                        <dl class="row mb-0 text-start">
                            <dt class="col-4">
                                <i class="fas fa-phone text-success"></i>
                            </dt>
                            <dd class="col-8" id="preview_telefono">
                                <small class="text-muted">Sin teléfono</small>
                            </dd>

                            <dt class="col-4">
                                <i class="fas fa-envelope text-info"></i>
                            </dt>
                            <dd class="col-8" id="preview_email">
                                <small class="text-muted">Sin email</small>
                            </dd>

                            <dt class="col-4">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                            </dt>
                            <dd class="col-8" id="preview_direccion">
                                <small class="text-muted">Sin dirección</small>
                            </dd>
                        </dl>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Registrar Proveedor
                            </button>
                            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>

                        <hr>

                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Nota:</strong> Solo el nombre es obligatorio. Los demás datos son opcionales.
                            </small>
                        </div>
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
    // Actualizar preview en tiempo real
    function actualizarPreview() {
        // Nombre
        const nombre = document.getElementById('nombre').value || 'Nombre del Proveedor';
        document.getElementById('preview_nombre').textContent = nombre;
        
        // Teléfono
        const telefono = document.getElementById('telefono').value;
        document.getElementById('preview_telefono').innerHTML = telefono 
            ? `<small>${telefono}</small>` 
            : '<small class="text-muted">Sin teléfono</small>';
        
        // Email
        const email = document.getElementById('email').value;
        document.getElementById('preview_email').innerHTML = email 
            ? `<small>${email}</small>` 
            : '<small class="text-muted">Sin email</small>';
        
        // Dirección
        const direccion = document.getElementById('direccion').value;
        document.getElementById('preview_direccion').innerHTML = direccion 
            ? `<small>${direccion}</small>` 
            : '<small class="text-muted">Sin dirección</small>';
    }
    
    // Event listeners
    document.getElementById('nombre').addEventListener('input', actualizarPreview);
    document.getElementById('telefono').addEventListener('input', actualizarPreview);
    document.getElementById('email').addEventListener('input', actualizarPreview);
    document.getElementById('direccion').addEventListener('input', actualizarPreview);
    
    // Validación del formulario
    document.getElementById('proveedorForm').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        
        if (nombre.length < 3) {
            e.preventDefault();
            alert('El nombre del proveedor debe tener al menos 3 caracteres');
            document.getElementById('nombre').focus();
            return false;
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

#preview_nombre {
    word-wrap: break-word;
}
</style>
@endpush
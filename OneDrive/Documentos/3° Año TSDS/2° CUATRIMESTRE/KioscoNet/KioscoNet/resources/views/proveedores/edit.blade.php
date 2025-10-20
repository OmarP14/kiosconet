@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-edit text-warning"></i>
                        Editar Proveedor
                    </h2>
                    <p class="text-muted mb-0">
                        Modifica la información del proveedor
                    </p>
                </div>
                <div>
                    <a href="{{ route('proveedores.show', $proveedor) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye me-2"></i>Ver Detalle
                    </a>
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
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
    <form action="{{ route('proveedores.update', $proveedor) }}" method="POST" id="proveedorForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            {{-- COLUMNA IZQUIERDA --}}
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
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
                                   value="{{ old('nombre', $proveedor->nombre) }}" 
                                   required
                                   autofocus>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                       value="{{ old('telefono', $proveedor->telefono) }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                       value="{{ old('email', $proveedor->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                      rows="3">{{ old('direccion', $proveedor->direccion) }}</textarea>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <i class="fas fa-truck fa-5x text-warning mb-3"></i>
                        <h5 id="preview_nombre" class="text-warning">{{ $proveedor->nombre }}</h5>
                        
                        <hr>
                        
                        <dl class="row mb-0 text-start">
                            <dt class="col-4">
                                <i class="fas fa-phone text-success"></i>
                            </dt>
                            <dd class="col-8" id="preview_telefono">
                                @if($proveedor->telefono)
                                    <small>{{ $proveedor->telefono }}</small>
                                @else
                                    <small class="text-muted">Sin teléfono</small>
                                @endif
                            </dd>

                            <dt class="col-4">
                                <i class="fas fa-envelope text-info"></i>
                            </dt>
                            <dd class="col-8" id="preview_email">
                                @if($proveedor->email)
                                    <small>{{ $proveedor->email }}</small>
                                @else
                                    <small class="text-muted">Sin email</small>
                                @endif
                            </dd>

                            <dt class="col-4">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                            </dt>
                            <dd class="col-8" id="preview_direccion">
                                @if($proveedor->direccion)
                                    <small>{{ $proveedor->direccion }}</small>
                                @else
                                    <small class="text-muted">Sin dirección</small>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                            <a href="{{ route('proveedores.show', $proveedor) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>Ver Detalle
                            </a>
                            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- INFO DEL SISTEMA --}}
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

                            <dt class="col-5">Creado:</dt>
                            <dd class="col-7">{{ $proveedor->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-5">Actualizado:</dt>
                            <dd class="col-7">{{ $proveedor->updated_at->format('d/m/Y H:i') }}</dd>
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
    // Actualizar preview en tiempo real
    function actualizarPreview() {
        const nombre = document.getElementById('nombre').value || 'Nombre del Proveedor';
        document.getElementById('preview_nombre').textContent = nombre;
        
        const telefono = document.getElementById('telefono').value;
        document.getElementById('preview_telefono').innerHTML = telefono 
            ? `<small>${telefono}</small>` 
            : '<small class="text-muted">Sin teléfono</small>';
        
        const email = document.getElementById('email').value;
        document.getElementById('preview_email').innerHTML = email 
            ? `<small>${email}</small>` 
            : '<small class="text-muted">Sin email</small>';
        
        const direccion = document.getElementById('direccion').value;
        document.getElementById('preview_direccion').innerHTML = direccion 
            ? `<small>${direccion}</small>` 
            : '<small class="text-muted">Sin dirección</small>';
    }
    
    document.getElementById('nombre').addEventListener('input', actualizarPreview);
    document.getElementById('telefono').addEventListener('input', actualizarPreview);
    document.getElementById('email').addEventListener('input', actualizarPreview);
    document.getElementById('direccion').addEventListener('input', actualizarPreview);
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
</style>
@endpush
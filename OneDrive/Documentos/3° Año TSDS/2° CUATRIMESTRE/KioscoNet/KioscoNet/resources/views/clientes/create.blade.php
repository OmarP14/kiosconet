@extends('layouts.app')

@section('title', isset($cliente) ? 'Editar Cliente' : 'Nuevo Cliente')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-user {{ isset($cliente) ? 'text-warning' : 'text-primary' }}"></i>
                        {{ isset($cliente) ? 'Editar Cliente' : 'Nuevo Cliente' }}
                    </h2>
                    <p class="text-muted mb-0">
                        {{ isset($cliente) ? 'Modifica la información del cliente' : 'Registra un nuevo cliente en el sistema' }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
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
    <form action="{{ isset($cliente) ? route('clientes.update', $cliente) : route('clientes.store') }}" 
          method="POST" 
          id="clienteForm">
        @csrf
        @if(isset($cliente))
            @method('PUT')
        @endif

        <div class="row">
            {{-- COLUMNA IZQUIERDA: INFORMACIÓN PERSONAL --}}
            <div class="col-md-8">
                {{-- DATOS PERSONALES --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- NOMBRE --}}
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>Nombre *
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $cliente->nombre ?? '') }}" 
                                       placeholder="Ej: Juan"
                                       required
                                       autofocus>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Nombre del cliente (obligatorio)
                                </small>
                            </div>

                            {{-- APELLIDO --}}
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>Apellido
                                </label>
                                <input type="text" 
                                       class="form-control @error('apellido') is-invalid @enderror" 
                                       id="apellido" 
                                       name="apellido" 
                                       value="{{ old('apellido', $cliente->apellido ?? '') }}" 
                                       placeholder="Ej: Pérez">
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Apellido del cliente (opcional)
                                </small>
                            </div>
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
                                       value="{{ old('telefono', $cliente->telefono ?? '') }}" 
                                       placeholder="264-1234567">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Teléfono de contacto
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
                                       value="{{ old('email', $cliente->email ?? '') }}" 
                                       placeholder="ejemplo@correo.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Email del cliente (opcional)
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            {{-- DIRECCIÓN --}}
                            <div class="col-md-12 mb-3">
                                <label for="direccion" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt me-1"></i>Dirección
                                </label>
                                <input type="text" 
                                       class="form-control @error('direccion') is-invalid @enderror" 
                                       id="direccion" 
                                       name="direccion" 
                                       value="{{ old('direccion', $cliente->direccion ?? '') }}" 
                                       placeholder="Ej: Av. Libertador 1234, San Juan">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Dirección completa del cliente
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFORMACIÓN COMERCIAL --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-store me-2"></i>Información Comercial
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- TIPO DE CLIENTE --}}
                            <div class="col-md-6 mb-3">
                                <label for="tipo_cliente" class="form-label fw-bold">
                                    <i class="fas fa-tag me-1"></i>Tipo de Cliente
                                </label>
                                <select class="form-select @error('tipo_cliente') is-invalid @enderror" 
                                        id="tipo_cliente" 
                                        name="tipo_cliente">
                                    <option value="minorista" {{ old('tipo_cliente', $cliente->tipo_cliente ?? 'minorista') == 'minorista' ? 'selected' : '' }}>
                                        Minorista (Consumidor final)
                                    </option>
                                    <option value="mayorista" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'mayorista' ? 'selected' : '' }}>
                                        Mayorista (Revende productos)
                                    </option>
                                    <option value="especial" {{ old('tipo_cliente', $cliente->tipo_cliente ?? '') == 'especial' ? 'selected' : '' }}>
                                        Especial (Condiciones particulares)
                                    </option>
                                </select>
                                @error('tipo_cliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Define el tipo de cliente según su perfil comercial
                                </small>
                            </div>

                            {{-- LÍMITE DE CRÉDITO --}}
                            <div class="col-md-6 mb-3">
                                <label for="limite_credito" class="form-label fw-bold">
                                    <i class="fas fa-credit-card me-1"></i>Límite de Crédito
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control @error('limite_credito') is-invalid @enderror" 
                                           id="limite_credito" 
                                           name="limite_credito" 
                                           value="{{ old('limite_credito', $cliente->limite_credito ?? 0) }}" 
                                           step="0.01" 
                                           min="0"
                                           placeholder="0.00">
                                </div>
                                @error('limite_credito')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Monto máximo que puede adeudar en cuenta corriente
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            {{-- ESTADO --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-toggle-on me-1"></i>Estado del Cliente
                                </label>
                                <div class="form-check form-switch" style="padding-left: 2.5rem;">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="activo" 
                                           name="activo" 
                                           value="1"
                                           style="width: 3rem; height: 1.5rem;"
                                           {{ old('activo', $cliente->activo ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        <span id="estadoTexto" class="badge bg-success">Activo</span>
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Clientes inactivos no pueden realizar compras
                                </small>
                            </div>

                            {{-- INFO ADICIONAL --}}
                            <div class="col-md-6 mb-3">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Importante:</strong><br>
                                    <small>
                                        • Límite $0 = Sin crédito (solo contado)<br>
                                        • Límite > $0 = Puede comprar fiado
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: RESUMEN Y ACCIONES --}}
            <div class="col-md-4">
                {{-- RESUMEN --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Resumen
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>

                        <dl class="row mb-0">
                            <dt class="col-7">Nombre:</dt>
                            <dd class="col-5 text-end" id="resumen_nombre">-</dd>

                            <dt class="col-7">Apellido:</dt>
                            <dd class="col-5 text-end" id="resumen_apellido">-</dd>

                            <dt class="col-7">Tipo:</dt>
                            <dd class="col-5 text-end" id="resumen_tipo">
                                <span class="badge bg-secondary">Minorista</span>
                            </dd>

                            <dt class="col-7">Límite Crédito:</dt>
                            <dd class="col-5 text-end fw-bold" id="resumen_limite">$0.00</dd>

                            <dt class="col-7">Estado:</dt>
                            <dd class="col-5 text-end" id="resumen_estado">
                                <span class="badge bg-success">Activo</span>
                            </dd>
                        </dl>

                        <hr>

                        <div class="alert alert-warning mb-0">
                            <small>
                                <i class="fas fa-lightbulb me-1"></i>
                                <strong>Tip:</strong> Asigna un límite de crédito solo a clientes de confianza.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                {{ isset($cliente) ? 'Guardar Cambios' : 'Registrar Cliente' }}
                            </button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>

                        @if(isset($cliente))
                        <hr>
                        <div class="text-muted small">
                            <i class="fas fa-clock me-1"></i>
                            Creado: {{ $cliente->created_at->format('d/m/Y H:i') }}
                            <br>
                            <i class="fas fa-edit me-1"></i>
                            Modificado: {{ $cliente->updated_at->format('d/m/Y H:i') }}
                        </div>
                        @endif
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
    // ACTUALIZAR RESUMEN EN TIEMPO REAL
    // ==========================================
    function actualizarResumen() {
        // Nombre
        const nombre = document.getElementById('nombre').value || '-';
        document.getElementById('resumen_nombre').textContent = nombre;
        
        // Apellido
        const apellido = document.getElementById('apellido').value || '-';
        document.getElementById('resumen_apellido').textContent = apellido;
        
        // Tipo de cliente
        const tipoCliente = document.getElementById('tipo_cliente');
        const tipoTexto = tipoCliente.options[tipoCliente.selectedIndex].text.split('(')[0].trim();
        let tipoBadgeClass = 'bg-secondary';
        
        if (tipoCliente.value === 'mayorista') {
            tipoBadgeClass = 'bg-primary';
        } else if (tipoCliente.value === 'especial') {
            tipoBadgeClass = 'bg-warning';
        }
        
        document.getElementById('resumen_tipo').innerHTML = 
            `<span class="badge ${tipoBadgeClass}">${tipoTexto}</span>`;
        
        // Límite de crédito
        const limite = parseFloat(document.getElementById('limite_credito').value) || 0;
        document.getElementById('resumen_limite').textContent = '$' + limite.toFixed(2);
    }
    
    // ==========================================
    // CAMBIAR ESTADO VISUAL DEL TOGGLE
    // ==========================================
    document.getElementById('activo').addEventListener('change', function() {
        const estadoTexto = document.getElementById('estadoTexto');
        const resumenEstado = document.getElementById('resumen_estado');
        
        if (this.checked) {
            estadoTexto.textContent = 'Activo';
            estadoTexto.className = 'badge bg-success';
            resumenEstado.innerHTML = '<span class="badge bg-success">Activo</span>';
        } else {
            estadoTexto.textContent = 'Inactivo';
            estadoTexto.className = 'badge bg-secondary';
            resumenEstado.innerHTML = '<span class="badge bg-secondary">Inactivo</span>';
        }
    });
    
    // ==========================================
    // VALIDACIÓN DEL FORMULARIO
    // ==========================================
    document.getElementById('clienteForm').addEventListener('submit', function(e) {
        const nombre = document.getElementById('nombre').value.trim();
        
        if (nombre.length < 2) {
            e.preventDefault();
            alert('El nombre debe tener al menos 2 caracteres');
            document.getElementById('nombre').focus();
            return false;
        }
    });
    
    // ==========================================
    // EVENT LISTENERS
    // ==========================================
    document.getElementById('nombre').addEventListener('input', actualizarResumen);
    document.getElementById('apellido').addEventListener('input', actualizarResumen);
    document.getElementById('tipo_cliente').addEventListener('change', actualizarResumen);
    document.getElementById('limite_credito').addEventListener('input', actualizarResumen);
    
    // Actualizar al cargar
    actualizarResumen();
});
</script>
@endpush

@push('styles')
<style>
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.card {
    border-radius: 15px;
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5em 0.8em;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

#resumen_nombre, #resumen_apellido {
    font-weight: 600;
}

@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush
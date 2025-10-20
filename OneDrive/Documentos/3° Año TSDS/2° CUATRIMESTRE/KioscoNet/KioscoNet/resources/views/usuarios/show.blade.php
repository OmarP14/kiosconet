@extends('layouts.app')

@section('title', 'Detalle del Usuario')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        @if($usuario->rol === 'administrador')
                            <i class="fas fa-user-shield text-danger"></i>
                        @else
                            <i class="fas fa-user-tag text-primary"></i>
                        @endif
                        {{ $usuario->nombre }}
                    </h2>
                    <p class="text-muted mb-0">
                        @if($usuario->rol === 'administrador')
                            <span class="badge bg-danger">
                                <i class="fas fa-shield-alt me-1"></i>Administrador
                            </span>
                        @else
                            <span class="badge bg-primary">
                                <i class="fas fa-store me-1"></i>Vendedor
                            </span>
                        @endif
                        <span class="ms-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Desde {{ $usuario->created_at->format('d/m/Y') }}
                        </span>
                    </p>
                </div>
                <div>
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- COLUMNA IZQUIERDA --}}
        <div class="col-md-8">
            {{-- INFORMACIÓN PERSONAL --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">
                                    <i class="fas fa-user text-primary me-1"></i>Nombre:
                                </dt>
                                <dd class="col-sm-8">
                                    <strong>{{ $usuario->nombre }}</strong>
                                </dd>

                                <dt class="col-sm-4">
                                    <i class="fas fa-envelope text-info me-1"></i>Email:
                                </dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:{{ $usuario->email }}" class="text-decoration-none">
                                        {{ $usuario->email }}
                                    </a>
                                </dd>
                            </dl>
                        </div>

                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">
                                    <i class="fas fa-at text-success me-1"></i>Usuario:
                                </dt>
                                <dd class="col-sm-8">
                                    <code>{{ $usuario->usuario }}</code>
                                </dd>

                                <dt class="col-sm-4">
                                    <i class="fas fa-shield-alt text-warning me-1"></i>Rol:
                                </dt>
                                <dd class="col-sm-8">
                                    @if($usuario->rol === 'administrador')
                                        <span class="badge bg-danger">Administrador</span>
                                    @else
                                        <span class="badge bg-primary">Vendedor</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ESTADÍSTICAS --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $estadisticas['total_ventas'] }}</h3>
                            <small>Ventas Totales</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $estadisticas['ventas_hoy'] }}</h3>
                            <small>Ventas Hoy</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $estadisticas['ventas_mes'] }}</h3>
                            <small>Ventas Este Mes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                            <h3 class="mb-0">${{ number_format($estadisticas['total_facturado'], 0) }}</h3>
                            <small>Total Facturado</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ÚLTIMAS VENTAS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Últimas Ventas ({{ $usuario->ventas->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($usuario->ventas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Método</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuario->ventas->take(10) as $venta)
                                <tr>
                                    <td>
                                        <small>{{ $venta->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($venta->cliente)
                                            {{ $venta->cliente->nombre }}
                                        @else
                                            <span class="text-muted">Cliente general</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>${{ number_format($venta->total, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($venta->metodo_pago === 'efectivo')
                                            <span class="badge bg-success">Efectivo</span>
                                        @else
                                            <span class="badge bg-primary">{{ ucfirst($venta->metodo_pago) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('ventas.show', $venta) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sin ventas registradas</h5>
                        <p class="text-muted">Este usuario aún no ha realizado ventas</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- MOVIMIENTOS DE CAJA --}}
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cash-register me-2"></i>
                        Últimos Movimientos de Caja
                    </h5>
                </div>
                <div class="card-body">
                    @if($usuario->movimientosCaja->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Concepto</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuario->movimientosCaja->take(10) as $movimiento)
                                <tr>
                                    <td>
                                        <small>{{ $movimiento->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($movimiento->tipo === 'ingreso')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-up"></i> Ingreso
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-down"></i> Egreso
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $movimiento->concepto }}</td>
                                    <td class="text-end">
                                        <strong>${{ number_format($movimiento->monto, 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-cash-register fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sin movimientos de caja</h5>
                        <p class="text-muted">Este usuario aún no ha registrado movimientos</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="col-md-4">
            {{-- FOTO DE PERFIL --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    @if($usuario->rol === 'administrador')
                        <i class="fas fa-user-shield fa-5x text-danger mb-3"></i>
                    @else
                        <i class="fas fa-user-tag fa-5x text-primary mb-3"></i>
                    @endif
                    
                    <h4 class="mb-1">{{ $usuario->nombre }}</h4>
                    <p class="text-muted mb-2">@{{ $usuario->usuario }}</p>
                    
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

            {{-- INFORMACIÓN ADICIONAL --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Información Adicional
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-6">ID Usuario:</dt>
                        <dd class="col-6 text-end">#{{ $usuario->id }}</dd>

                        <dt class="col-6">Fecha Registro:</dt>
                        <dd class="col-6 text-end">{{ $usuario->created_at->format('d/m/Y') }}</dd>

                        <dt class="col-6">Última Actualización:</dt>
                        <dd class="col-6 text-end">{{ $usuario->updated_at->format('d/m/Y') }}</dd>

                        <dt class="col-6">Promedio Venta:</dt>
                        <dd class="col-6 text-end">
                            <strong>${{ number_format($estadisticas['promedio_venta'], 2) }}</strong>
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- PERMISOS DEL ROL --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Permisos
                    </h5>
                </div>
                <div class="card-body">
                    @if($usuario->rol === 'administrador')
                        <ul class="mb-0">
                            <li><i class="fas fa-check text-success"></i> Acceso total al sistema</li>
                            <li><i class="fas fa-check text-success"></i> Gestionar usuarios</li>
                            <li><i class="fas fa-check text-success"></i> Gestionar productos</li>
                            <li><i class="fas fa-check text-success"></i> Ver todos los reportes</li>
                            <li><i class="fas fa-check text-success"></i> Configurar sistema</li>
                            <li><i class="fas fa-check text-success"></i> Gestionar proveedores</li>
                        </ul>
                    @else
                        <ul class="mb-0">
                            <li><i class="fas fa-check text-success"></i> Realizar ventas</li>
                            <li><i class="fas fa-check text-success"></i> Ver productos y stock</li>
                            <li><i class="fas fa-check text-success"></i> Gestionar clientes</li>
                            <li><i class="fas fa-check text-success"></i> Ver reportes básicos</li>
                            <li><i class="fas fa-check text-success"></i> Gestionar caja</li>
                            <li><i class="fas fa-times text-danger"></i> Gestionar usuarios</li>
                            <li><i class="fas fa-times text-danger"></i> Configurar sistema</li>
                        </ul>
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
                        <a href="{{ route('usuarios.edit', $usuario) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar Usuario
                        </a>

                        <a href="mailto:{{ $usuario->email }}" 
                           class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>

                        <button type="button" 
                                class="btn btn-info"
                                onclick="resetearPassword({{ $usuario->id }}, '{{ $usuario->nombre }}')">
                            <i class="fas fa-key me-2"></i>Resetear Contraseña
                        </button>

                        <hr>

                        @if(auth()->id() !== $usuario->id)
                        <button type="button" 
                                class="btn btn-danger"
                                onclick="eliminarUsuario({{ $usuario->id }}, '{{ $usuario->nombre }}', {{ $usuario->ventas->count() }}, '{{ $usuario->rol }}')">
                            <i class="fas fa-trash me-2"></i>Eliminar Usuario
                        </button>
                        @else
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                No puedes eliminar tu propio usuario
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ACTIVIDAD RECIENTE --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-1"></i>Actividad Reciente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @if($usuario->ventas->first())
                        <div class="timeline-item">
                            <i class="fas fa-shopping-cart text-success"></i>
                            <div>
                                <small class="text-muted">Última venta</small>
                                <br>
                                <small>{{ $usuario->ventas->first()->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endif

                        @if($usuario->movimientosCaja->first())
                        <div class="timeline-item">
                            <i class="fas fa-cash-register text-info"></i>
                            <div>
                                <small class="text-muted">Último mov. caja</small>
                                <br>
                                <small>{{ $usuario->movimientosCaja->first()->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endif

                        <div class="timeline-item">
                            <i class="fas fa-user-edit text-warning"></i>
                            <div>
                                <small class="text-muted">Última actualización</small>
                                <br>
                                <small>{{ $usuario->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <i class="fas fa-user-plus text-primary"></i>
                            <div>
                                <small class="text-muted">Usuario creado</small>
                                <br>
                                <small>{{ $usuario->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

{{-- MODAL RESETEAR CONTRASEÑA --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key text-warning"></i> 
                    Resetear Contraseña
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="resetPasswordForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>¿Está seguro que desea resetear la contraseña de <strong id="nombreUsuarioReset"></strong>?</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        La nueva contraseña será: <strong>password123</strong>
                        <br>
                        <small>El usuario deberá cambiarla después de iniciar sesión</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-2"></i>Resetear Contraseña
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
    
    alertaVentas.classList.add('d-none');
    alertaAdministrador.classList.add('d-none');
    btnEliminar.disabled = false;
    btnEliminar.classList.remove('disabled');
    
    if (cantidadVentas > 0) {
        alertaVentas.classList.remove('d-none');
        btnEliminar.disabled = true;
        btnEliminar.classList.add('disabled');
    }
    
    if (rol === 'administrador') {
        alertaAdministrador.classList.remove('d-none');
    }
    
    const modal = new bootstrap.Modal(document.getElementById('eliminarUsuarioModal'));
    modal.show();
}

function resetearPassword(usuarioId, nombre) {
    document.getElementById('nombreUsuarioReset').textContent = nombre;
    document.getElementById('resetPasswordForm').action = `/usuarios/${usuarioId}/reset-password`;
    
    const modal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
    modal.show();
}
</script>
@endpush

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 10px 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 15px;
    align-items: flex-start;
}

.timeline-item i {
    margin-right: 10px;
    margin-top: 3px;
    font-size: 1.2rem;
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
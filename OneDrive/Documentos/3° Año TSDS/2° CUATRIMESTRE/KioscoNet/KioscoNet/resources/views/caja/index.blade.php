@extends('layouts.app')

@section('title', 'Control de Caja')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-cash-register"></i> Control de Caja
            </h1>
           <div>
                <a href="{{ route('caja.arqueo') }}" class="btn btn-warning me-2">
                    <i class="fas fa-calculator"></i> Arqueo
                </a>
                <button type="button" class="btn btn-success me-2" onclick="registrarMovimiento('ingreso')">
                    <i class="fas fa-plus"></i> Ingreso
                </button>
                <button type="button" class="btn btn-danger" onclick="registrarMovimiento('egreso')">
                    <i class="fas fa-minus"></i> Egreso
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Resumen de caja actual -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{ number_format($saldoActual, 2) }}</h4>
                        <p class="mb-0">Saldo Actual</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-wallet fa-2x"></i>
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
                        <h4 class="mb-0">${{ number_format($ingresosHoy, 2) }}</h4>
                        <p class="mb-0">Ingresos Hoy</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-arrow-up fa-2x"></i>
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
                        <h4 class="mb-0">${{ number_format($egresosHoy, 2) }}</h4>
                        <p class="mb-0">Egresos Hoy</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-arrow-down fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">${{ number_format($ventasHoy, 2) }}</h4>
                        <p class="mb-0">Ventas Hoy</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter"></i> Filtros de Movimientos
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('caja.index') }}" id="filtrosForm">
            <div class="row">
                <div class="col-md-3">
                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_desde" 
                           name="fecha_desde" 
                           value="{{ request('fecha_desde', date('Y-m-01')) }}">
                </div>
                <div class="col-md-3">
                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_hasta" 
                           name="fecha_hasta" 
                           value="{{ request('fecha_hasta', date('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo de Movimiento</label>
                    <select class="form-select" id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="ingreso" {{ request('tipo') == 'ingreso' ? 'selected' : '' }}>Ingresos</option>
                        <option value="egreso" {{ request('tipo') == 'egreso' ? 'selected' : '' }}>Egresos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="usuario_id" class="form-label">Usuario</label>
                    <select class="form-select" id="usuario_id" name="usuario_id">
                        <option value="">Todos</option>
                        @foreach($usuarios ?? [] as $usuario)
                        <option value="{{ $usuario->id }}" {{ request('usuario_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('caja.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportarMovimientos()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de movimientos -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Movimientos de Caja
        </h5>
    </div>
    <div class="card-body">
        @if($movimientos->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Tipo</th>
                        <th>Concepto</th>
                        <th>Usuario</th>
                        <th class="text-end">Monto</th>
                        <th class="text-end">Saldo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $movimiento)
                    <tr>
                        <td>
                            {{ $movimiento->created_at->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ $movimiento->created_at->format('H:i:s') }}</small>
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
                        <td>
                            <strong>{{ $movimiento->concepto }}</strong>
                            @if($movimiento->descripcion)
                                <br><small class="text-muted">{{ $movimiento->descripcion }}</small>
                            @endif
                        </td>
                        <td>
                            <i class="fas fa-user"></i> {{ $movimiento->usuario->name }}
                        </td>
                        <td class="text-end">
                            <strong class="{{ $movimiento->tipo === 'ingreso' ? 'text-success' : 'text-danger' }}">
                                {{ $movimiento->tipo === 'ingreso' ? '+' : '-' }}${{ number_format($movimiento->monto, 2) }}
                            </strong>
                        </td>
                        <td class="text-end">
                            <strong>${{ number_format($movimiento->saldo_posterior ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        title="Ver detalle"
                                        onclick="verDetalle({{ $movimiento->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(!str_contains($movimiento->concepto, 'Venta #'))
                                    @can('editar_caja')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-warning" 
                                            title="Editar"
                                            onclick="editarMovimiento({{ $movimiento->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan
                                    @can('eliminar_movimientos_caja')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar"
                                            onclick="eliminarMovimiento({{ $movimiento->id }}, '{{ $movimiento->concepto }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                @endif
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
                    Mostrando {{ $movimientos->firstItem() }} a {{ $movimientos->lastItem() }} 
                    de {{ $movimientos->total() }} resultados
                </small>
            </div>
            <div>
                {{ $movimientos->appends(request()->query())->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-cash-register fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay movimientos de caja</h5>
            <p class="text-muted">No se encontraron movimientos con los filtros aplicados.</p>
            <button type="button" class="btn btn-primary" onclick="registrarMovimiento('ingreso')">
                <i class="fas fa-plus"></i> Registrar Primer Movimiento
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Modal para registrar movimiento -->
<div class="modal fade" id="movimientoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle"></i> 
                    <span id="tituloModal">Registrar Movimiento</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="movimientoForm" method="POST" action="{{ route('caja.store') }}">
                @csrf
                <input type="hidden" id="movimiento_id" name="movimiento_id">
                <input type="hidden" id="metodo_form" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tipo_movimiento" class="form-label">Tipo de Movimiento *</label>
                        <select class="form-select" id="tipo_movimiento" name="tipo" required>
                            <option value="ingreso">
                                <i class="fas fa-arrow-up"></i> Ingreso
                            </option>
                            <option value="egreso">
                                <i class="fas fa-arrow-down"></i> Egreso
                            </option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="concepto" class="form-label">Concepto *</label>
                        <input type="text" 
                               class="form-control" 
                               id="concepto" 
                               name="concepto" 
                               placeholder="Ej: Venta de productos, Pago servicios, etc."
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="monto" 
                                   name="monto" 
                                   step="0.01" 
                                   min="0.01" 
                                   placeholder="0.00"
                                   required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción Adicional</label>
                        <textarea class="form-control" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="3" 
                                  placeholder="Detalles adicionales del movimiento (opcional)"></textarea>
                    </div>
                    
                    <!-- Información del saldo actual -->
                    <div class="alert alert-info">
                        <strong>Saldo actual de caja:</strong> ${{ number_format($saldoActual, 2) }}
                        <br>
                        <small id="preview_saldo" class="text-muted">
                            Ingrese un monto para ver el saldo resultante
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <i class="fas fa-save"></i> Registrar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver detalle -->
<div class="modal fade" id="detalleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Detalle del Movimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">Fecha:</dt>
                    <dd class="col-sm-8" id="detalle_fecha"></dd>
                    
                    <dt class="col-sm-4">Tipo:</dt>
                    <dd class="col-sm-8" id="detalle_tipo"></dd>
                    
                    <dt class="col-sm-4">Concepto:</dt>
                    <dd class="col-sm-8" id="detalle_concepto"></dd>
                    
                    <dt class="col-sm-4">Monto:</dt>
                    <dd class="col-sm-8" id="detalle_monto"></dd>
                    
                    <dt class="col-sm-4">Usuario:</dt>
                    <dd class="col-sm-8" id="detalle_usuario"></dd>
                    
                    <dt class="col-sm-4">Saldo Posterior:</dt>
                    <dd class="col-sm-8" id="detalle_saldo"></dd>
                    
                    <dt class="col-sm-4">Descripción:</dt>
                    <dd class="col-sm-8" id="detalle_descripcion"></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para eliminar movimiento -->
<div class="modal fade" id="eliminarMovimientoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Eliminar Movimiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eliminarMovimientoForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el movimiento <strong id="conceptoEliminar"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> 
                        Esta acción afectará el saldo de caja y no se puede deshacer.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const saldoActual = {{ $saldoActual }};

/**
 * Registrar nuevo movimiento
 */
function registrarMovimiento(tipo = 'ingreso') {
    limpiarFormulario();
    $('#tipo_movimiento').val(tipo).change();
    $('#tituloModal').text(`Registrar ${tipo === 'ingreso' ? 'Ingreso' : 'Egreso'}`);
    $('#movimientoForm').attr('action', '{{ route("caja.store") }}');
    $('#metodo_form').val('POST');
    $('#btnGuardar').html('<i class="fas fa-save"></i> Registrar Movimiento');
    $('#movimientoModal').modal('show');
}

/**
 * Editar movimiento existente
 */
function editarMovimiento(movimientoId) {
    // Aquí harías una llamada AJAX para obtener los datos del movimiento
    $.get(`/caja/${movimientoId}`, function(data) {
        $('#movimiento_id').val(data.id);
        $('#tipo_movimiento').val(data.tipo);
        $('#concepto').val(data.concepto);
        $('#monto').val(data.monto);
        $('#descripcion').val(data.descripcion);
        $('#tituloModal').text('Editar Movimiento');
        $('#movimientoForm').attr('action', `/caja/${data.id}`);
        $('#metodo_form').val('PUT');
        $('#btnGuardar').html('<i class="fas fa-save"></i> Actualizar Movimiento');
        $('#movimientoModal').modal('show');
    });
}

/**
 * Ver detalle del movimiento
 */
function verDetalle(movimientoId) {
    $.get(`/caja/${movimientoId}`, function(data) {
        $('#detalle_fecha').text(new Date(data.created_at).toLocaleString());
        $('#detalle_tipo').html(data.tipo === 'ingreso' 
            ? '<span class="badge bg-success"><i class="fas fa-arrow-up"></i> Ingreso</span>'
            : '<span class="badge bg-danger"><i class="fas fa-arrow-down"></i> Egreso</span>');
        $('#detalle_concepto').text(data.concepto);
        $('#detalle_monto').html(`<strong class="${data.tipo === 'ingreso' ? 'text-success' : 'text-danger'}">
            ${data.tipo === 'ingreso' ? '+' : '-'}${parseFloat(data.monto).toFixed(2)}</strong>`);
        $('#detalle_usuario').text(data.usuario.name);
        $('#detalle_saldo').html(`<strong>${parseFloat(data.saldo_posterior || 0).toFixed(2)}</strong>`);
        $('#detalle_descripcion').text(data.descripcion || 'Sin descripción adicional');
        $('#detalleModal').modal('show');
    });
}

/**
 * Eliminar movimiento
 */
function eliminarMovimiento(movimientoId, concepto) {
    $('#conceptoEliminar').text(concepto);
    $('#eliminarMovimientoForm').attr('action', `/caja/${movimientoId}`);
    $('#eliminarMovimientoModal').modal('show');
}

/**
 * Exportar movimientos
 */
function exportarMovimientos() {
    const form = document.getElementById('filtrosForm');
    const formData = new FormData(form);
    
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    params.append('export', 'excel');
    window.location.href = `{{ route('caja.index') }}?${params.toString()}`;
}

/**
 * Limpiar formulario
 */
function limpiarFormulario() {
    $('#movimientoForm')[0].reset();
    $('#movimiento_id').val('');
    $('#preview_saldo').text('Ingrese un monto para ver el saldo resultante');
}

$(document).ready(function() {
    // Preview del saldo al cambiar monto o tipo
    $('#monto, #tipo_movimiento').on('input change', function() {
        const monto = parseFloat($('#monto').val()) || 0;
        const tipo = $('#tipo_movimiento').val();
        
        if (monto > 0) {
            const saldoResultante = tipo === 'ingreso' 
                ? saldoActual + monto 
                : saldoActual - monto;
            
            const clase = saldoResultante >= 0 ? 'text-success' : 'text-danger';
            $('#preview_saldo').html(`
                <strong>Saldo resultante: <span class="${clase}">${saldoResultante.toFixed(2)}</span></strong>
            `);
        } else {
            $('#preview_saldo').text('Ingrese un monto para ver el saldo resultante');
        }
    });
    
    // Cambiar color del botón según el tipo
    $('#tipo_movimiento').change(function() {
        const tipo = $(this).val();
        $('#btnGuardar').removeClass('btn-success btn-danger').addClass(
            tipo === 'ingreso' ? 'btn-success' : 'btn-danger'
        );
    });
    
    // Validación del formulario
    $('#movimientoForm').submit(function(e) {
        const monto = parseFloat($('#monto').val()) || 0;
        const tipo = $('#tipo_movimiento').val();
        
        if (monto <= 0) {
            e.preventDefault();
            alert('El monto debe ser mayor a cero');
            $('#monto').focus();
            return false;
        }
        
        if (tipo === 'egreso' && monto > saldoActual) {
            if (!confirm(`El egreso (${monto.toFixed(2)}) es mayor al saldo actual (${saldoActual.toFixed(2)}). ¿Desea continuar?`)) {
                e.preventDefault();
                return false;
            }
        }
        
        const concepto = $('#concepto').val().trim();
        if (concepto.length < 3) {
            e.preventDefault();
            alert('El concepto debe tener al menos 3 caracteres');
            $('#concepto').focus();
            return false;
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

.text-success {
    font-weight: 600;
}

.text-danger {
    font-weight: 600;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
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
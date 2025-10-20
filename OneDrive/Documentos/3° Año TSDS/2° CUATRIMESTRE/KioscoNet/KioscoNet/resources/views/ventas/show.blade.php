@extends('layouts.app')

@section('title', 'Detalle de Venta #' . $venta->numero)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-receipt"></i> 
                Venta #{{ $venta->numero }}
                @if($venta->anulada)
                    <span class="badge bg-danger ms-2">ANULADA</span>
                @endif
            </h1>
            <div>
                <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
                <a href="{{ route('ventas.ticket', $venta->id) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-print"></i> Imprimir Ticket
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información de la venta -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle"></i> Información de la Venta
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Número:</dt>
                            <dd class="col-sm-8">{{ $venta->numero }}</dd>
                            
                            <dt class="col-sm-4">Fecha:</dt>
                            <dd class="col-sm-8">
                                {{ $venta->created_at->format('d/m/Y H:i:s') }}
                                <small class="text-muted">
                                    ({{ $venta->created_at->diffForHumans() }})
                                </small>
                            </dd>
                            
                            <dt class="col-sm-4">Vendedor:</dt>
                            <dd class="col-sm-8">
                                <i class="fas fa-user-tag"></i> {{ $venta->usuario->name }}
                            </dd>
                            
                            <dt class="col-sm-4">Cliente:</dt>
                            <dd class="col-sm-8">
                                @if($venta->cliente)
                                    <i class="fas fa-user"></i> {{ $venta->cliente->nombre }}
                                    @if($venta->cliente->telefono)
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone"></i> {{ $venta->cliente->telefono }}
                                        </small>
                                    @endif
                                @else
                                    <i class="fas fa-user-times text-muted"></i> Cliente Final
                                @endif
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Método de Pago:</dt>
                            <dd class="col-sm-7">
                                @switch($venta->metodo_pago)
                                    @case('efectivo')
                                        <i class="fas fa-money-bill text-success"></i> Efectivo
                                        @break
                                    @case('tarjeta')
                                        <i class="fas fa-credit-card text-primary"></i> Tarjeta
                                        @break
                                    @case('transferencia')
                                        <i class="fas fa-exchange-alt text-info"></i> Transferencia
                                        @break
                                    @case('cc')
                                        <i class="fas fa-file-invoice text-warning"></i> Cuenta Corriente
                                        @break
                                    @default
                                        {{ ucfirst($venta->metodo_pago) }}
                                @endswitch
                            </dd>
                            
                            @if($venta->metodo_pago === 'efectivo')
                            <dt class="col-sm-5">Monto Recibido:</dt>
                            <dd class="col-sm-7">${{ number_format($venta->monto_recibido, 2) }}</dd>
                            
                            <dt class="col-sm-5">Vuelto:</dt>
                            <dd class="col-sm-7">
                                <span class="{{ $venta->vuelto > 0 ? 'text-info' : 'text-muted' }}">
                                    ${{ number_format($venta->vuelto, 2) }}
                                </span>
                            </dd>
                            @endif
                            
                            <dt class="col-sm-5">Total:</dt>
                            <dd class="col-sm-7">
                                <h4 class="text-success mb-0">
                                    ${{ number_format($venta->total, 2) }}
                                </h4>
                            </dd>
                        </dl>
                    </div>
                </div>

                @if($venta->anulada)
                <hr>
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle"></i> Venta Anulada</h6>
                    @if($venta->fecha_anulacion)
                    <p class="mb-1">
                        <strong>Fecha de Anulación:</strong> 
                        {{ $venta->fecha_anulacion->format('d/m/Y H:i:s') }}
                    </p>
                    @endif
                    @if(isset($venta->usuarioAnulacion) && $venta->usuarioAnulacion)
                    <p class="mb-1">
                        <strong>Anulada por:</strong> {{ $venta->usuarioAnulacion->name }}
                    </p>
                    @endif
                    @if($venta->motivo_anulacion)
                    <p class="mb-0">
                        <strong>Motivo:</strong> {{ $venta->motivo_anulacion }}
                    </p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Detalle de productos -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart"></i> 
                    Detalle de Productos ({{ $venta->detalles->count() }} item{{ $venta->detalles->count() != 1 ? 's' : '' }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $detalle)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $detalle->producto->nombre }}</strong>
                                        @if($detalle->producto->codigo)
                                            <br><small class="text-muted">Código: {{ $detalle->producto->codigo }}</small>
                                        @endif
                                        @if($detalle->producto->categoria)
                                            <br><small class="text-muted">{{ $detalle->producto->categoria }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $detalle->cantidad }}</span>
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalle->precio_unitario, 2) }}
                                </td>
                                <td class="text-end">
                                    <strong>${{ number_format($detalle->subtotal, 2) }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">TOTAL:</th>
                                <th class="text-end">
                                    <h5 class="text-success mb-0">
                                        ${{ number_format($venta->total, 2) }}
                                    </h5>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel lateral con resumen -->
    <div class="col-md-4">
        <!-- Resumen rápido -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie"></i> Resumen
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $venta->detalles->count() }}</h4>
                            <small class="text-muted">Productos</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-0">{{ $venta->detalles->sum('cantidad') }}</h4>
                        <small class="text-muted">Unidades</small>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <strong>${{ number_format($venta->total, 2) }}</strong>
                </div>
                @if($venta->metodo_pago === 'efectivo' && $venta->vuelto > 0)
                <div class="d-flex justify-content-between text-muted">
                    <span>Recibido:</span>
                    <span>${{ number_format($venta->monto_recibido, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between text-info">
                    <span>Vuelto:</span>
                    <span>${{ number_format($venta->vuelto, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Acciones -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs"></i> Acciones
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('ventas.ticket', $venta->id) }}" 
                       class="btn btn-primary" 
                       target="_blank">
                        <i class="fas fa-print"></i> Imprimir Ticket
                    </a>
                    
                    <a href="{{ route('ventas.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nueva Venta
                    </a>
                    
                    @if($venta->cliente)
                    <a href="{{ route('clientes.show', $venta->cliente->id) }}" class="btn btn-info">
                        <i class="fas fa-user"></i> Ver Cliente
                    </a>
                    @endif
                    
                    @if(!$venta->anulada && auth()->user()->rol === 'administrador')
                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="anularVenta({{ $venta->id }}, '{{ $venta->numero }}')">
                            <i class="fas fa-times"></i> Anular Venta
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información del cliente -->
        @if($venta->cliente)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user"></i> Información del Cliente
                </h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-5">Nombre:</dt>
                    <dd class="col-7">{{ $venta->cliente->nombre }}</dd>
                    
                    @if($venta->cliente->email)
                    <dt class="col-5">Email:</dt>
                    <dd class="col-7">
                        <small>{{ $venta->cliente->email }}</small>
                    </dd>
                    @endif
                    
                    @if($venta->cliente->telefono)
                    <dt class="col-5">Teléfono:</dt>
                    <dd class="col-7">
                        <small>{{ $venta->cliente->telefono }}</small>
                    </dd>
                    @endif
                    
                    @if($venta->metodo_pago === 'cc')
                    <dt class="col-5">Saldo Actual:</dt>
                    <dd class="col-7">
                        <span class="{{ $venta->cliente->saldo_cc >= 0 ? 'text-success' : 'text-danger' }}">
                            ${{ number_format($venta->cliente->saldo_cc, 2) }}
                        </span>
                    </dd>
                    @endif
                </dl>
                
                <div class="d-grid">
                    <a href="{{ route('clientes.show', $venta->cliente->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Ver Historial Completo
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal para anular venta -->
<div class="modal fade" id="anularVentaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Anular Venta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="anularVentaForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea anular la venta <strong id="numeroVenta"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> 
                        Esta acción:
                        <ul class="mb-0 mt-2">
                            <li>Restaurará el stock de todos los productos</li>
                            <li>Ajustará los movimientos de caja</li>
                            @if($venta->metodo_pago === 'cc')
                            <li>Restaurará el saldo del cliente</li>
                            @endif
                            <li>No se podrá deshacer</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label for="motivo_anulacion" class="form-label">Motivo de Anulación *</label>
                        <textarea class="form-control" 
                                  id="motivo_anulacion" 
                                  name="motivo" 
                                  rows="3" 
                                  placeholder="Ingrese el motivo de la anulación (mínimo 5 caracteres)..."
                                  required
                                  minlength="5"
                                  maxlength="255"></textarea>
                        <div class="form-text">
                            <span id="contador_caracteres">0</span>/255 caracteres
                        </div>
                        <div class="invalid-feedback" id="error_motivo">
                            El motivo debe tener entre 5 y 255 caracteres.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="btnConfirmarAnulacion" disabled>
                        <i class="fas fa-check"></i> Confirmar Anulación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // Validación en tiempo real del motivo
    $('#motivo_anulacion').on('input', function() {
        const motivo = $(this).val().trim();
        const contador = $('#contador_caracteres');
        const botonConfirmar = $('#btnConfirmarAnulacion');
        const campoMotivo = $(this);
        
        contador.text(motivo.length);
        
        if (motivo.length >= 5 && motivo.length <= 255) {
            botonConfirmar.prop('disabled', false);
            campoMotivo.removeClass('is-invalid').addClass('is-valid');
            $('#error_motivo').hide();
        } else {
            botonConfirmar.prop('disabled', true);
            campoMotivo.removeClass('is-valid').addClass('is-invalid');
            $('#error_motivo').show();
        }
    });
    
    // Validación del formulario
    $('#anularVentaForm').on('submit', function(e) {
        const motivo = $('#motivo_anulacion').val().trim();
        const numeroVenta = $('#numeroVenta').text();
        
        if (motivo.length < 5 || motivo.length > 255) {
            e.preventDefault();
            alert('El motivo debe tener entre 5 y 255 caracteres.');
            $('#motivo_anulacion').focus();
            return false;
        }
        
        const confirmMessage = `¿Está completamente seguro de anular la venta ${numeroVenta}?\n\n` +
                              `ESTA ACCIÓN:\n` +
                              `• Restaurará el stock de todos los productos\n` +
                              `• Ajustará los movimientos de caja\n` +
                              `• No se podrá deshacer\n\n` +
                              `Motivo: ${motivo}`;
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }
        
        const botonConfirmar = $('#btnConfirmarAnulacion');
        botonConfirmar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
        
        return true;
    });
    
    // Resetear modal al cerrar
    $('#anularVentaModal').on('hidden.bs.modal', function() {
        $('#anularVentaForm')[0].reset();
        $('#motivo_anulacion').removeClass('is-valid is-invalid');
        $('#contador_caracteres').text('0');
        $('#btnConfirmarAnulacion').prop('disabled', true).html('<i class="fas fa-check"></i> Confirmar Anulación');
        $('#error_motivo').hide();
    });
});

/**
 * Función para inicializar modal de anulación
 */
function anularVenta(ventaId, numeroVenta) {
    $('#numeroVenta').text(numeroVenta);
    $('#anularVentaForm').attr('action', `/ventas/${ventaId}`);
    $('#motivo_anulacion').val('').removeClass('is-valid is-invalid');
    $('#contador_caracteres').text('0');
    $('#btnConfirmarAnulacion').prop('disabled', true);
    $('#error_motivo').hide();
    
    const modal = new bootstrap.Modal(document.getElementById('anularVentaModal'));
    modal.show();
    
    $('#anularVentaModal').on('shown.bs.modal', function() {
        $('#motivo_anulacion').focus();
    });
}
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    border-top: none;
    font-weight: 600;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}

dl.row dt {
    font-weight: 600;
    color: #495057;
}

dl.row dd {
    color: #6c757d;
}

.badge {
    font-size: 0.875em;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-valid {
    border-color: #198754;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 1s linear infinite;
}

@media (max-width: 768px) {
    .d-grid .btn {
        margin-bottom: 0.5rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush
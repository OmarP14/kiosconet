@extends('layouts.app')

@section('title', 'Cuentas Corrientes')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-file-invoice-dollar text-warning"></i>
                        Cuentas Corrientes
                    </h2>
                    <p class="text-muted mb-0">
                        Gestión de saldos y deudas de clientes
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-success me-2" onclick="imprimirReporte()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- RESUMEN FINANCIERO --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Clientes Deudores</h6>
                            <h3 class="mb-0">{{ $resumen['total_deudores'] }}</h3>
                            <small>clientes deben dinero</small>
                        </div>
                        <div>
                            <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Con Crédito</h6>
                            <h3 class="mb-0">{{ $resumen['total_acreedores'] }}</h3>
                            <small>clientes con saldo a favor</small>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Deuda</h6>
                            <h3 class="mb-0 text-danger">
                                ${{ number_format($resumen['monto_total_deuda'], 2) }}
                            </h3>
                            <small>monto total adeudado</small>
                        </div>
                        <div>
                            <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Balance Neto</h6>
                            <h3 class="mb-0 {{ $resumen['balance_neto'] >= 0 ? 'text-white' : 'text-danger' }}">
                                ${{ number_format($resumen['balance_neto'], 2) }}
                            </h3>
                            <small>deuda - créditos</small>
                        </div>
                        <div>
                            <i class="fas fa-balance-scale fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <ul class="nav nav-tabs mb-3" id="cuentasTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="deudores-tab" data-bs-toggle="tab" data-bs-target="#deudores" type="button">
                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                Deudores ({{ $resumen['total_deudores'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="acreedores-tab" data-bs-toggle="tab" data-bs-target="#acreedores" type="button">
                <i class="fas fa-check-circle text-success me-2"></i>
                Con Crédito a Favor ({{ $resumen['total_acreedores'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="todos-tab" data-bs-toggle="tab" data-bs-target="#todos" type="button">
                <i class="fas fa-list text-primary me-2"></i>
                Todos los Movimientos
            </button>
        </li>
    </ul>

    <div class="tab-content" id="cuentasTabContent">
        {{-- TAB DEUDORES --}}
        <div class="tab-pane fade show active" id="deudores" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Clientes Deudores - Deben Dinero
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $deudores = $clientes->where('saldo_cc', '<', 0)->sortBy('saldo_cc');
                    @endphp

                    @if($deudores->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th class="text-center">Última Compra</th>
                                    <th class="text-end">Saldo Deudor</th>
                                    <th class="text-end">Límite</th>
                                    <th class="text-end">Disponible</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deudores as $cliente)
                                <tr class="{{ $cliente->limite_credito + $cliente->saldo_cc < 0 ? 'table-danger' : '' }}">
                                    <td>
                                        <div>
                                            <strong>{{ $cliente->nombre }} {{ $cliente->apellido }}</strong>
                                            @if($cliente->direccion)
                                                <br><small class="text-muted">
                                                    <i class="fas fa-map-marker-alt"></i> {{ $cliente->direccion }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($cliente->telefono)
                                            <i class="fas fa-phone text-success"></i> {{ $cliente->telefono }}<br>
                                        @endif
                                        @if($cliente->email)
                                            <i class="fas fa-envelope text-info"></i> {{ $cliente->email }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($cliente->ventas->count() > 0)
                                            @php $ultimaVenta = $cliente->ventas->first(); @endphp
                                            {{ $ultimaVenta->created_at->format('d/m/Y') }}
                                            <br><small class="text-muted">{{ $ultimaVenta->created_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-danger">
                                            ${{ number_format(abs($cliente->saldo_cc), 2) }}
                                        </strong>
                                        <br><small class="text-danger">DEBE</small>
                                    </td>
                                    <td class="text-end">
                                        <span class="text-primary">
                                            ${{ number_format($cliente->limite_credito, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @php
                                            $disponible = $cliente->limite_credito + $cliente->saldo_cc;
                                        @endphp
                                        <strong class="{{ $disponible >= 0 ? 'text-success' : 'text-danger' }}">
                                            ${{ number_format($disponible, 2) }}
                                        </strong>
                                        @if($disponible < 0)
                                            <br><small class="text-danger">¡SIN CRÉDITO!</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($disponible < 0)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-ban"></i> Límite Excedido
                                            </span>
                                        @elseif($disponible < 500)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-triangle"></i> Poco Crédito
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> OK
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('clientes.show', $cliente) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success" 
                                                    title="Registrar pago"
                                                    onclick="ajustarSaldo({{ $cliente->id }}, '{{ $cliente->nombre }} {{ $cliente->apellido }}', {{ $cliente->saldo_cc }})">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                            @if($cliente->telefono)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}?text=Hola {{ $cliente->nombre }}, tienes una deuda pendiente de ${{ number_format(abs($cliente->saldo_cc), 2) }}" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-success" 
                                               title="WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-danger">
                                <tr>
                                    <th colspan="3" class="text-end">TOTAL ADEUDADO:</th>
                                    <th class="text-end">
                                        <strong class="text-danger fs-5">
                                            ${{ number_format($resumen['monto_total_deuda'], 2) }}
                                        </strong>
                                    </th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-success">¡Excelente!</h5>
                        <p class="text-muted">No hay clientes con deudas pendientes</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB ACREEDORES --}}
        <div class="tab-pane fade" id="acreedores" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Clientes con Crédito a Favor
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $acreedores = $clientes->where('saldo_cc', '>', 0)->sortByDesc('saldo_cc');
                    @endphp

                    @if($acreedores->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th class="text-end">Saldo a Favor</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acreedores as $cliente)
                                <tr>
                                    <td>
                                        <strong>{{ $cliente->nombre }} {{ $cliente->apellido }}</strong>
                                        @if($cliente->direccion)
                                            <br><small class="text-muted">{{ $cliente->direccion }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($cliente->telefono)
                                            <i class="fas fa-phone text-success"></i> {{ $cliente->telefono }}<br>
                                        @endif
                                        @if($cliente->email)
                                            <i class="fas fa-envelope text-info"></i> {{ $cliente->email }}
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">
                                            ${{ number_format($cliente->saldo_cc, 2) }}
                                        </strong>
                                        <br><small class="text-success">A FAVOR</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('clientes.show', $cliente) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info"
                                                    onclick="ajustarSaldo({{ $cliente->id }}, '{{ $cliente->nombre }} {{ $cliente->apellido }}', {{ $cliente->saldo_cc }})">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-success">
                                <tr>
                                    <th colspan="2" class="text-end">TOTAL CRÉDITO A FAVOR:</th>
                                    <th class="text-end">
                                        <strong class="text-success fs-5">
                                            ${{ number_format($resumen['monto_total_credito'], 2) }}
                                        </strong>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sin Créditos a Favor</h5>
                        <p class="text-muted">No hay clientes con saldo positivo</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB TODOS --}}
        <div class="tab-pane fade" id="todos" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Todos los Movimientos de Cuenta Corriente
                    </h5>
                </div>
                <div class="card-body">
                    @if($clientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th class="text-end">Saldo</th>
                                    <th class="text-end">Límite</th>
                                    <th class="text-end">Disponible</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientes->sortBy('saldo_cc') as $cliente)
                                <tr>
                                    <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                                    <td class="text-end">
                                        <span class="{{ $cliente->saldo_cc >= 0 ? 'text-success' : 'text-danger' }}">
                                            ${{ number_format($cliente->saldo_cc, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-end">${{ number_format($cliente->limite_credito, 2) }}</td>
                                    <td class="text-end">
                                        @php $disp = $cliente->limite_credito + $cliente->saldo_cc; @endphp
                                        <span class="{{ $disp >= 0 ? 'text-success' : 'text-danger' }}">
                                            ${{ number_format($disp, 2) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ ucfirst($cliente->tipo_cliente ?? 'minorista') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL AJUSTAR SALDO --}}
<div class="modal fade" id="ajustarSaldoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-dollar-sign"></i> Registrar Pago / Ajustar Saldo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajustarSaldoForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Cliente: <strong id="nombreCliente"></strong>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label for="saldo_actual" class="form-label">Saldo Actual</label>
                            <input type="number" class="form-control" id="saldo_actual" step="0.01" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="saldo_nuevo" class="form-label">Nuevo Saldo *</label>
                            <input type="number" class="form-control" id="saldo_nuevo" name="saldo_cc" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="motivo_ajuste" class="form-label">Motivo *</label>
                        <textarea class="form-control" id="motivo_ajuste" name="motivo" rows="3" 
                                  placeholder="Ej: Pago en efectivo, Transferencia, Ajuste por error..." required></textarea>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Tip:</strong> Para registrar un pago, aumenta el saldo. 
                            Por ejemplo, si debe $100 y paga $50, el nuevo saldo sería -$50.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ajustarSaldo(clienteId, nombre, saldoActual) {
    document.getElementById('nombreCliente').textContent = nombre;
    document.getElementById('saldo_actual').value = saldoActual.toFixed(2);
    document.getElementById('saldo_nuevo').value = saldoActual.toFixed(2);
    document.getElementById('motivo_ajuste').value = '';
    document.getElementById('ajustarSaldoForm').action = `/clientes/${clienteId}/ajustar-saldo`;
    
    const modal = new bootstrap.Modal(document.getElementById('ajustarSaldoModal'));
    modal.show();
}

function imprimirReporte() {
    window.print();
}

// Calcular diferencia en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const saldoNuevo = document.getElementById('saldo_nuevo');
    if (saldoNuevo) {
        saldoNuevo.addEventListener('input', function() {
            const actual = parseFloat(document.getElementById('saldo_actual').value) || 0;
            const nuevo = parseFloat(this.value) || 0;
            const diferencia = nuevo - actual;
            
            // Remover mensaje anterior
            const msgAnterior = document.getElementById('msg_diferencia');
            if (msgAnterior) msgAnterior.remove();
            
            // Mostrar nuevo mensaje
            if (diferencia !== 0) {
                const msg = document.createElement('small');
                msg.id = 'msg_diferencia';
                msg.className = diferencia > 0 ? 'text-success' : 'text-danger';
                msg.textContent = diferencia > 0 
                    ? `Se agregará $${diferencia.toFixed(2)} al saldo`
                    : `Se descontará $${Math.abs(diferencia).toFixed(2)} del saldo`;
                this.parentElement.appendChild(msg);
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.opacity-50 {
    opacity: 0.5;
}

.table-danger {
    --bs-table-bg: rgba(220, 53, 69, 0.1);
}

@media print {
    .btn, .nav-tabs, .modal {
        display: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        page-break-inside: avoid;
    }
}
</style>
@endpush
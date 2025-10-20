@extends('layouts.app')

@section('title', 'Arqueo de Caja')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-calculator text-success"></i>
                        Arqueo de Caja
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ now()->format('l, d \d\e F \d\e Y') }}
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary me-2" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                    <a href="{{ route('caja.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- COLUMNA IZQUIERDA --}}
        <div class="col-md-8">
            {{-- RESUMEN DEL DÍA --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Resumen del Día
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3 border-end">
                                <i class="fas fa-arrow-up fa-2x text-success mb-2"></i>
                                <h4 class="text-success mb-0">
                                    ${{ number_format($totalIngresos, 2) }}
                                </h4>
                                <small class="text-muted">Total Ingresos</small>
                                <br>
                                <small class="badge bg-success">{{ $ingresos->count() }} movimientos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border-end">
                                <i class="fas fa-arrow-down fa-2x text-danger mb-2"></i>
                                <h4 class="text-danger mb-0">
                                    ${{ number_format($totalEgresos, 2) }}
                                </h4>
                                <small class="text-muted">Total Egresos</small>
                                <br>
                                <small class="badge bg-danger">{{ $egresos->count() }} movimientos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border-end">
                                <i class="fas fa-shopping-cart fa-2x text-info mb-2"></i>
                                <h4 class="text-info mb-0">
                                    ${{ number_format($ventasHoy, 2) }}
                                </h4>
                                <small class="text-muted">Ventas del Día</small>
                                <br>
                                <small class="badge bg-info">Efectivo: ${{ number_format($ventasEfectivo, 2) }}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <i class="fas fa-wallet fa-2x text-primary mb-2"></i>
                                <h4 class="text-primary mb-0">
                                    ${{ number_format($saldoDelDia, 2) }}
                                </h4>
                                <small class="text-muted">Saldo del Día</small>
                                <br>
                                <small class="badge bg-primary">Neto: I - E</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MOVIMIENTOS DEL DÍA --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Movimientos del Día ({{ $movimientos->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($movimientos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Hora</th>
                                    <th>Tipo</th>
                                    <th>Concepto</th>
                                    <th>Usuario</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movimientos as $movimiento)
                                <tr>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($movimiento->created_at)->format('H:i:s') }}</small>
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
                                        <small>{{ $movimiento->concepto }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $movimiento->usuario_nombre ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $movimiento->tipo === 'ingreso' ? 'text-success' : 'text-danger' }}">
                                            {{ $movimiento->tipo === 'ingreso' ? '+' : '-' }}${{ number_format($movimiento->monto, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">BALANCE DEL DÍA:</th>
                                    <th class="text-end">
                                        <span class="text-primary fs-5">
                                            ${{ number_format($saldoDelDia, 2) }}
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sin movimientos hoy</h5>
                        <p class="text-muted">No se han registrado movimientos en el día de hoy</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="col-md-4">
            {{-- SALDO TOTAL DE CAJA --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Saldo Total en Caja
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-success mb-0">
                        ${{ number_format($saldoActual, 2) }}
                    </h2>
                    <p class="text-muted mb-0">Saldo acumulado</p>
                    <hr>
                    <small class="text-muted">
                        Este es el saldo total desde que se empezó a usar el sistema
                    </small>
                </div>
            </div>

            {{-- FORMULARIO DE CIERRE --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding-usd me-2"></i>
                        Conteo Físico
                    </h5>
                </div>
                <div class="card-body">
                    <form id="cierreForm" method="POST" action="{{ route('caja.cerrarCaja') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="monto_fisico" class="form-label fw-bold">
                                💵 Dinero Contado en Caja *
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control" 
                                       id="monto_fisico" 
                                       name="monto_fisico" 
                                       step="0.01" 
                                       min="0"
                                       placeholder="0.00"
                                       required>
                            </div>
                            <small class="text-muted">
                                Cuenta todo el efectivo que hay en la caja
                            </small>
                        </div>

                        <div class="alert alert-info mb-3">
                            <strong>📊 Saldo según sistema:</strong>
                            <br>
                            ${{ number_format($saldoActual, 2) }}
                        </div>

                        <div id="diferencia_alert" class="alert d-none mb-3"></div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">
                                📝 Observaciones
                            </label>
                            <textarea class="form-control" 
                                      id="observaciones" 
                                      name="observaciones" 
                                      rows="3" 
                                      placeholder="Notas adicionales sobre el cierre..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-lock me-2"></i>
                                Registrar Cierre de Caja
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- AYUDA --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-1"></i>
                        ¿Cómo hacer el arqueo?
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li class="mb-2">Cuenta todo el dinero físico en la caja</li>
                        <li class="mb-2">Ingresa el monto contado arriba</li>
                        <li class="mb-2">El sistema comparará con el saldo esperado</li>
                        <li class="mb-2">Si hay diferencia, se registrará automáticamente</li>
                        <li>Haz clic en "Registrar Cierre"</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const saldoSistema = {{ $saldoActual }};

document.addEventListener('DOMContentLoaded', function() {
    // Calcular diferencia en tiempo real
    const montoFisicoInput = document.getElementById('monto_fisico');
    const diferenciaAlert = document.getElementById('diferencia_alert');
    
    montoFisicoInput.addEventListener('input', function() {
        const montoFisico = parseFloat(this.value) || 0;
        const diferencia = montoFisico - saldoSistema;
        
        if (montoFisico > 0) {
            diferenciaAlert.classList.remove('d-none');
            
            if (Math.abs(diferencia) < 0.01) {
                // Coincide perfecto
                diferenciaAlert.className = 'alert alert-success mb-3';
                diferenciaAlert.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <strong>¡Perfecto!</strong> El monto físico coincide con el sistema.
                `;
            } else if (diferencia > 0) {
                // Sobra dinero
                diferenciaAlert.className = 'alert alert-warning mb-3';
                diferenciaAlert.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Sobrante:</strong> $${Math.abs(diferencia).toFixed(2)}
                    <br><small>Hay más dinero físico que en el sistema</small>
                `;
            } else {
                // Falta dinero
                diferenciaAlert.className = 'alert alert-danger mb-3';
                diferenciaAlert.innerHTML = `
                    <i class="fas fa-times-circle"></i>
                    <strong>Faltante:</strong> $${Math.abs(diferencia).toFixed(2)}
                    <br><small>Hay menos dinero físico que en el sistema</small>
                `;
            }
        } else {
            diferenciaAlert.classList.add('d-none');
        }
    });
    
    // Validación del formulario
    document.getElementById('cierreForm').addEventListener('submit', function(e) {
        const montoFisico = parseFloat(montoFisicoInput.value) || 0;
        const diferencia = montoFisico - saldoSistema;
        
        if (montoFisico <= 0) {
            e.preventDefault();
            alert('Debe ingresar el monto físico contado en caja');
            montoFisicoInput.focus();
            return false;
        }
        
        // Advertencia si hay diferencia significativa
        if (Math.abs(diferencia) > 100) {
            if (!confirm(`Hay una diferencia de $${Math.abs(diferencia).toFixed(2)}. ¿Desea continuar con el cierre?`)) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.border-end {
    border-right: 1px solid #dee2e6 !important;
}

@media print {
    .btn, .card-header {
        display: none !important;
    }
    
    #cierreForm {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        page-break-inside: avoid;
    }
}
</style>
@endpush
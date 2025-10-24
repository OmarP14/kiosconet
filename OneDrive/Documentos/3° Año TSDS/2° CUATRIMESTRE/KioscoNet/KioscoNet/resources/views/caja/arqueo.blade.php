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
                                <small class="badge bg-info">Todas las ventas</small>
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

            {{-- ✅ NUEVO: DESGLOSE POR MÉTODO DE PAGO --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Desglose por Método de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- EFECTIVO --}}
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 h-100" style="background-color: #f0f9ff;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-money-bill-wave fa-2x text-success me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">💵 EFECTIVO</small>
                                        <h5 class="mb-0 text-success">${{ number_format($ventasEfectivo, 2) }}</h5>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Debe estar en caja física
                                </small>
                            </div>
                        </div>

                        {{-- TARJETA --}}
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 h-100" style="background-color: #fff4e6;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-credit-card fa-2x text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">💳 TARJETA</small>
                                        <h5 class="mb-0 text-primary">${{ number_format($ventasTarjeta, 2) }}</h5>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-university"></i> Va a cuenta bancaria
                                </small>
                            </div>
                        </div>

                        {{-- TRANSFERENCIA --}}
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 h-100" style="background-color: #f0fff4;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exchange-alt fa-2x text-info me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">🏦 TRANSFERENCIA</small>
                                        <h5 class="mb-0 text-info">${{ number_format($ventasTransferencia, 2) }}</h5>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-university"></i> Va a cuenta bancaria
                                </small>
                            </div>
                        </div>

                        {{-- CUENTA CORRIENTE --}}
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 h-100" style="background-color: #fef3f2;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-warning me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">📋 CUENTA CORRIENTE</small>
                                        <h5 class="mb-0 text-warning">${{ number_format($ventasCC, 2) }}</h5>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-handshake"></i> Crédito a clientes
                                </small>
                            </div>
                        </div>
                    </div>

                    @if($ventasMixto > 0)
                    <div class="alert alert-info mt-3 mb-0">
                        <strong><i class="fas fa-coins"></i> Pagos Mixtos:</strong> ${{ number_format($ventasMixto, 2) }}
                        <br>
                        <small>
                            Desglosados:
                            Efectivo ${{ number_format($mixtoDesglose['efectivo'], 2) }} |
                            Tarjeta ${{ number_format($mixtoDesglose['tarjeta'], 2) }} |
                            Transferencia ${{ number_format($mixtoDesglose['transferencia'], 2) }}
                        </small>
                    </div>
                    @endif

                    <hr class="my-3">
                    <div class="text-center">
                        <h5 class="mb-1">
                            <strong>TOTAL VENTAS:</strong>
                            <span class="text-primary">${{ number_format($ventasHoy, 2) }}</span>
                        </h5>
                        <small class="text-muted">Suma de todos los métodos de pago</small>
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
            {{-- GANANCIA DEL DÍA --}}
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        💰 Ganancia del Día
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <small class="text-muted text-uppercase">Ingresos - Egresos</small>
                    </div>
                    <h1 class="display-4 mb-3 {{ $saldoDelDia >= 0 ? 'text-success' : 'text-danger' }}" style="font-weight: 700;">
                        {{ $saldoDelDia >= 0 ? '+' : '' }}${{ number_format($saldoDelDia, 2) }}
                    </h1>
                    <div class="row text-center small">
                        <div class="col-6 border-end">
                            <div class="text-success fw-bold">+${{ number_format($totalIngresos, 2) }}</div>
                            <div class="text-muted">Ingresos</div>
                        </div>
                        <div class="col-6">
                            <div class="text-danger fw-bold">-${{ number_format($totalEgresos, 2) }}</div>
                            <div class="text-muted">Egresos</div>
                        </div>
                    </div>
                </div>
            </div>

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

            {{-- ✅ NUEVO: CÁLCULO DE EFECTIVO ESPERADO --}}
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        💵 Efectivo Esperado en Caja
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-plus text-success"></i> Ventas en Efectivo</td>
                                    <td class="text-end text-success">+${{ number_format($ventasEfectivo, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-plus text-success"></i> Otros Ingresos en Efectivo</td>
                                    <td class="text-end text-success">+${{ number_format($otrosIngresosEfectivo, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-minus text-danger"></i> Egresos en Efectivo</td>
                                    <td class="text-end text-danger">-${{ number_format($egresosEfectivo, 2) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <th><strong>EFECTIVO QUE DEBE HABER:</strong></th>
                                    <th class="text-end">
                                        <h5 class="mb-0 text-success">
                                            ${{ number_format($efectivoEsperado, 2) }}
                                        </h5>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning mt-3 mb-0">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            <strong>Importante:</strong> Solo cuenta el <strong>efectivo físico</strong> (billetes y monedas).
                            No incluyas tarjeta, transferencias ni MercadoPago.
                        </small>
                    </div>
                </div>
            </div>

            {{-- FORMULARIO DE CIERRE --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding-usd me-2"></i>
                        Conteo Físico de Efectivo
                    </h5>
                </div>
                <div class="card-body">
                    <form id="cierreForm" method="POST" action="{{ route('caja.cerrarCaja') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="monto_fisico" class="form-label fw-bold">
                                💵 Efectivo Contado en Caja *
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-success text-white">$</span>
                                <input type="number"
                                       class="form-control form-control-lg"
                                       id="monto_fisico"
                                       name="monto_fisico"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       required
                                       style="font-size: 1.5rem; font-weight: bold;">
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-hand-holding-usd"></i>
                                Cuenta SOLO billetes y monedas físicas en la caja
                            </small>
                        </div>

                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>📊 Efectivo esperado en sistema:</strong>
                                </div>
                                <div>
                                    <h4 class="mb-0 text-success">${{ number_format($efectivoEsperado, 2) }}</h4>
                                </div>
                            </div>
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
                                      placeholder="Notas adicionales sobre el cierre (ej: diferencias encontradas, billetes rotos, etc)..."></textarea>
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
                        ¿Cómo hacer el arqueo correctamente?
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li class="mb-2">
                            <strong>Cuenta SOLO el efectivo físico</strong> (billetes y monedas) que está en la caja
                        </li>
                        <li class="mb-2">
                            <strong>NO incluyas</strong> dinero de tarjeta, transferencias ni MercadoPago (ese va al banco)
                        </li>
                        <li class="mb-2">
                            Ingresa el monto total contado en el campo "Efectivo Contado"
                        </li>
                        <li class="mb-2">
                            El sistema comparará automáticamente con el efectivo esperado
                        </li>
                        <li class="mb-2">
                            Si hay diferencia (sobrante o faltante), se registrará y deberás explicar el motivo
                        </li>
                        <li>
                            Haz clic en "Registrar Cierre de Caja"
                        </li>
                    </ol>

                    <hr class="my-3">

                    <div class="alert alert-info mb-0 small">
                        <strong><i class="fas fa-lightbulb"></i> Consejo:</strong>
                        <br>
                        Al finalizar el día, verifica en tu banco/plataforma que coincida el dinero de tarjeta, transferencias y MercadoPago con lo que muestra el sistema arriba.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ✅ NUEVO: Comparar con efectivo esperado (no con saldo total)
const efectivoEsperado = {{ $efectivoEsperado }};

document.addEventListener('DOMContentLoaded', function() {
    // Calcular diferencia en tiempo real
    const montoFisicoInput = document.getElementById('monto_fisico');
    const diferenciaAlert = document.getElementById('diferencia_alert');

    montoFisicoInput.addEventListener('input', function() {
        const montoFisico = parseFloat(this.value) || 0;
        const diferencia = montoFisico - efectivoEsperado;

        if (montoFisico > 0) {
            diferenciaAlert.classList.remove('d-none');

            if (Math.abs(diferencia) < 0.01) {
                // Coincide perfecto
                diferenciaAlert.className = 'alert alert-success mb-3';
                diferenciaAlert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <strong>✅ ¡Perfecto!</strong> El efectivo contado coincide con el sistema.
                            <br><small>No hay diferencias en el arqueo de efectivo.</small>
                        </div>
                    </div>
                `;
            } else if (diferencia > 0) {
                // Sobra dinero
                diferenciaAlert.className = 'alert alert-warning mb-3';
                diferenciaAlert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <strong>⚠️ Sobrante de Efectivo:</strong> $${Math.abs(diferencia).toFixed(2)}
                            <br><small>Hay MÁS dinero físico del que debería haber según el sistema</small>
                        </div>
                    </div>
                `;
            } else {
                // Falta dinero
                diferenciaAlert.className = 'alert alert-danger mb-3';
                diferenciaAlert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle fa-2x me-3"></i>
                        <div>
                            <strong>❌ Faltante de Efectivo:</strong> $${Math.abs(diferencia).toFixed(2)}
                            <br><small>Hay MENOS dinero físico del que debería haber según el sistema</small>
                        </div>
                    </div>
                `;
            }
        } else {
            diferenciaAlert.classList.add('d-none');
        }
    });

    // Validación del formulario
    document.getElementById('cierreForm').addEventListener('submit', function(e) {
        const montoFisico = parseFloat(montoFisicoInput.value) || 0;
        const diferencia = montoFisico - efectivoEsperado;

        if (montoFisico < 0) {
            e.preventDefault();
            alert('❌ Debe ingresar el efectivo físico contado en caja');
            montoFisicoInput.focus();
            return false;
        }

        // Advertencia si hay diferencia significativa
        if (Math.abs(diferencia) > 100) {
            const tipo = diferencia > 0 ? 'SOBRANTE' : 'FALTANTE';
            const mensaje = `⚠️ HAY UN ${tipo} DE EFECTIVO DE $${Math.abs(diferencia).toFixed(2)}\n\n` +
                          `Efectivo esperado: $${efectivoEsperado.toFixed(2)}\n` +
                          `Efectivo contado: $${montoFisico.toFixed(2)}\n\n` +
                          `¿Desea continuar con el cierre?`;

            if (!confirm(mensaje)) {
                e.preventDefault();
                return false;
            }
        }

        // Confirmación final
        if (!confirm('¿Confirma que desea registrar el cierre de caja con estos datos?')) {
            e.preventDefault();
            return false;
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
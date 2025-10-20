@extends('layouts.app')

@section('title', 'Listado de Ventas')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-shopping-cart text-primary"></i> Ventas
                    </h2>
                    <p class="text-muted mb-0">Listado de todas las ventas registradas</p>
                </div>
                <div>
                    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Venta
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('ventas.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" 
                           value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" 
                           value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Método de Pago</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="">Todos</option>
                        <option value="efectivo" {{ request('metodo_pago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta" {{ request('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="cc" {{ request('metodo_pago') == 'cc' ? 'selected' : '' }}>Cuenta Corriente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLA DE VENTAS --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-list me-2"></i>Ventas Registradas ({{ $ventas->total() }})
                </h6>
                <div class="text-muted small">
                    Total: <strong class="text-success">${{ number_format($ventas->sum('total'), 2) }}</strong>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($ventas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Número</th>
                            <th class="border-0">Fecha</th>
                            <th class="border-0">Cliente</th>
                            <th class="border-0">Método Pago</th>
                            <th class="border-0">Total</th>
                            <th class="border-0">Vendedor</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventas as $venta)
                        <tr>
                            <td class="fw-bold text-primary">{{ $venta->numero }}</td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <i class="fas fa-user text-muted me-2"></i>
                                {{ optional($venta->cliente)->nombre ?? 'Cliente de Contado' }}
                            </td>
                            <td>
                                @php
                                    $metodoBadge = [
                                        'efectivo' => 'success',
                                        'tarjeta' => 'info',
                                        'transferencia' => 'warning',
                                        'billetera' => 'secondary',
                                        'cc' => 'primary'
                                    ];
                                    $metodosNombre = [
                                        'efectivo' => 'Efectivo',
                                        'tarjeta' => 'Tarjeta',
                                        'transferencia' => 'Transferencia',
                                        'billetera' => 'Billetera',
                                        'cc' => 'C. Corriente'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $metodoBadge[$venta->metodo_pago] ?? 'secondary' }}">
                                    {{ $metodosNombre[$venta->metodo_pago] ?? $venta->metodo_pago }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">
                                ${{ number_format($venta->total, 2) }}
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ optional($venta->usuario)->nombre ?? 'N/A' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('ventas.show', $venta->id) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            onclick="imprimirTicket({{ $venta->id }})"
                                            title="Imprimir ticket">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    @if($venta->puedeAnularse())
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="anularVenta({{ $venta->id }})"
                                            title="Anular venta">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total en esta página:</th>
                            <th class="text-success">${{ number_format($ventas->sum('total'), 2) }}</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay ventas registradas</h5>
                <p class="text-muted">Comienza creando tu primera venta</p>
                <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nueva Venta
                </a>
            </div>
            @endif
        </div>
        
        @if($ventas->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Mostrando {{ $ventas->firstItem() }} a {{ $ventas->lastItem() }} de {{ $ventas->total() }} ventas
                </small>
                {{ $ventas->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL ANULAR VENTA --}}
<div class="modal fade" id="modalAnular" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-ban me-2"></i>Anular Venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAnular" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción no se puede deshacer. El stock de los productos será devuelto.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Motivo de Anulación *</label>
                        <textarea class="form-control" name="motivo_anulacion" rows="3" 
                                  placeholder="Explique el motivo de la anulación..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-2"></i>Anular Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Función para anular venta
function anularVenta(ventaId) {
    const modal = new bootstrap.Modal(document.getElementById('modalAnular'));
    const form = document.getElementById('formAnular');
    form.action = `/ventas/${ventaId}/anular`;
    modal.show();
}

// Función para imprimir ticket
function imprimirTicket(ventaId) {
    window.open(`/ventas/${ventaId}/ticket`, '_blank', 'width=800,height=600');
}

// Manejar envío del formulario de anulación
document.getElementById('formAnular').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al anular la venta');
    }
});
</script>
@endpush

@push('styles')
<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
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
</style>
@endpush
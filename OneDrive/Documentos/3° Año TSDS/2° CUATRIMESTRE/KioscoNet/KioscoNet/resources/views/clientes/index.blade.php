@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-users"></i> Gestión de Clientes
            </h1>
            <div>
                <a href="{{ route('clientes.cuentas-corrientes') }}" class="btn btn-warning me-2">
                    <i class="fas fa-file-invoice-dollar"></i> Cuentas Corrientes
                </a>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Cliente
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filtros de búsqueda -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-search"></i> Filtros de Búsqueda
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('clientes.index') }}" id="filtrosForm">
            <div class="row">
                <div class="col-md-4">
                    <label for="buscar" class="form-label">Buscar Cliente</label>
                   <input type="text" 
                        class="form-control" 
                        id="buscar" 
                        name="buscar" 
                        value="{{ request('buscar') }}"
                        placeholder="Nombre, apellido, teléfono o email...">
                </div>
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos</option>
                        <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="saldo_cc" class="form-label">Cuenta Corriente</label>
                    <select class="form-select" id="saldo_cc" name="saldo_cc">
                        <option value="">Todos</option>
                        <option value="con_saldo" {{ request('saldo_cc') === 'con_saldo' ? 'selected' : '' }}>Con Saldo</option>
                        <option value="deudores" {{ request('saldo_cc') === 'deudores' ? 'selected' : '' }}>Deudores</option>
                        <option value="sin_movimientos" {{ request('saldo_cc') === 'sin_movimientos' ? 'selected' : '' }}>Sin Movimientos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="orden" class="form-label">Ordenar por</label>
                    <select class="form-select" id="orden" name="orden">
                        <option value="nombre" {{ request('orden') === 'nombre' ? 'selected' : '' }}>Nombre</option>
                        <option value="reciente" {{ request('orden') === 'reciente' ? 'selected' : '' }}>Más Reciente</option>
                        <option value="saldo_desc" {{ request('orden') === 'saldo_desc' ? 'selected' : '' }}>Mayor Saldo</option>
                        <option value="saldo_asc" {{ request('orden') === 'saldo_asc' ? 'selected' : '' }}>Menor Saldo</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportarClientes()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Estadísticas rápidas -->
@if($clientes->count() > 0)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $clientes->total() }}</h4>
                        <p class="mb-0">Total Clientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
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
                        <h4 class="mb-0">{{ $clientes->where('activo', true)->count() }}</h4>
                        <p class="mb-0">Clientes Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $clientes->where('saldo_cc', '<', 0)->count() }}</h4>
                        <p class="mb-0">Con Deuda</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
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
                        <h4 class="mb-0">${{ number_format($clientes->sum('saldo_cc'), 2) }}</h4>
                        <p class="mb-0">Saldo Total CC</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Tabla de clientes -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Listado de Clientes
        </h5>
    </div>
    <div class="card-body">
        @if($clientes->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th class="text-center">Última Compra</th>
                        <th class="text-end">Total Compras</th>
                        <th class="text-end">Saldo CC</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    <tr class="{{ !$cliente->activo ? 'table-secondary' : '' }}">
                        <td>
                            <div>
                                {{-- ✅ MOSTRAR NOMBRE COMPLETO --}}
                                <strong>
                                    {{ $cliente->nombre }}
                                    @if($cliente->apellido)
                                        {{ $cliente->apellido }}
                                    @endif
                                </strong>
                                
                                {{-- Mostrar tipo de cliente --}}
                                @if($cliente->tipo_cliente)
                                    <br>
                                    <small>
                                        @if($cliente->tipo_cliente === 'minorista')
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                <i class="fas fa-user"></i> Minorista
                                            </span>
                                        @elseif($cliente->tipo_cliente === 'mayorista')
                                            <span class="badge bg-primary" style="font-size: 0.7rem;">
                                                <i class="fas fa-store"></i> Mayorista
                                            </span>
                                        @elseif($cliente->tipo_cliente === 'especial')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                                <i class="fas fa-star"></i> Especial
                                            </span>
                                        @endif
                                    </small>
                                @endif
                                
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
                            @if(!$cliente->telefono && !$cliente->email)
                                <span class="text-muted">Sin contacto</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($cliente->ventas->count() > 0)
                                @php $ultimaVenta = $cliente->ventas->first(); @endphp
                                {{ $ultimaVenta->created_at->format('d/m/Y') }}
                                <br><small class="text-muted">{{ $ultimaVenta->created_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Sin compras</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($cliente->ventas->count() > 0)
                                <strong>${{ number_format($cliente->ventas->sum('total'), 2) }}</strong>
                                <br><small class="text-muted">{{ $cliente->ventas->count() }} compras</small>
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($cliente->saldo_cc != 0)
                                <strong class="{{ $cliente->saldo_cc >= 0 ? 'text-success' : 'text-danger' }}">
                                    ${{ number_format($cliente->saldo_cc, 2) }}
                                </strong>
                                @if($cliente->saldo_cc < 0)
                                    <br><small class="text-danger">DEBE</small>
                                @else
                                    <br><small class="text-success">CRÉDITO</small>
                                @endif
                            @else
                                <span class="text-muted">$0.00</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($cliente->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('clientes.show', $cliente) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente) }}" 
                                   class="btn btn-sm btn-outline-warning" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($cliente->saldo_cc != 0)
                                <button type="button" 
                                        class="btn btn-sm btn-outline-info" 
                                        title="Ajustar saldo"
                                        onclick="ajustarSaldo({{ $cliente->id }}, '{{ $cliente->nombre }}', {{ $cliente->saldo_cc }})">
                                    <i class="fas fa-dollar-sign"></i>
                                </button>
                                @endif
                                @can('eliminar_clientes')
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Eliminar"
                                        onclick="eliminarCliente({{ $cliente->id }}, '{{ $cliente->nombre }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
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
                    Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }} 
                    de {{ $clientes->total() }} resultados
                </small>
            </div>
            <div>
                {{ $clientes->appends(request()->query())->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No se encontraron clientes</h5>
            <p class="text-muted">No hay clientes registrados con los filtros aplicados.</p>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Primer Cliente
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal para ajustar saldo -->
<div class="modal fade" id="ajustarSaldoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-dollar-sign"></i> Ajustar Saldo Cuenta Corriente
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
                            <input type="number" 
                                   class="form-control" 
                                   id="saldo_actual" 
                                   step="0.01"
                                   readonly 
                                   style="background-color: #f8f9fa;">
                        </div>
                        <div class="col-md-6">
                            <label for="saldo_nuevo" class="form-label">Nuevo Saldo *</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="saldo_nuevo" 
                                   name="saldo_cc" 
                                   step="0.01"
                                   required>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="motivo_ajuste_saldo" class="form-label">Motivo del Ajuste *</label>
                        <textarea class="form-control" 
                                  id="motivo_ajuste_saldo" 
                                  name="motivo" 
                                  rows="3" 
                                  placeholder="Ej: Pago efectivo, Ajuste por error, Bonificación, etc."
                                  required></textarea>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Nota:</strong> Los valores positivos representan crédito a favor del cliente, 
                            los valores negativos representan deuda del cliente.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar cliente -->
<div class="modal fade" id="eliminarClienteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Eliminar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eliminarClienteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el cliente <strong id="nombreClienteEliminar"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> 
                        Esta acción eliminará permanentemente:
                        <ul class="mb-0 mt-2">
                            <li>Los datos del cliente</li>
                            <li>El historial de cuenta corriente</li>
                        </ul>
                        <strong>Las ventas realizadas se mantendrán para fines contables.</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/**
 * Función para ajustar saldo de cuenta corriente
 */
function ajustarSaldo(clienteId, nombre, saldoActual) {
    $('#nombreCliente').text(nombre);
    $('#saldo_actual').val(saldoActual.toFixed(2));
    $('#saldo_nuevo').val(saldoActual.toFixed(2));
    $('#motivo_ajuste_saldo').val('');
    $('#ajustarSaldoForm').attr('action', `/clientes/${clienteId}/ajustar-saldo`);
    $('#ajustarSaldoModal').modal('show');
}

/**
 * Función para eliminar un cliente
 */
function eliminarCliente(clienteId, nombre) {
    $('#nombreClienteEliminar').text(nombre);
    $('#eliminarClienteForm').attr('action', `/clientes/${clienteId}`);
    $('#eliminarClienteModal').modal('show');
}

/**
 * Función para exportar clientes
 */
function exportarClientes() {
    const form = document.getElementById('filtrosForm');
    const formData = new FormData(form);
    
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    params.append('export', 'excel');
    window.location.href = `{{ route('clientes.index') }}?${params.toString()}`;
}

$(document).ready(function() {
    // Calcular diferencia de saldo al cambiar
    $('#saldo_nuevo').on('input', function() {
        const saldoActual = parseFloat($('#saldo_actual').val()) || 0;
        const saldoNuevo = parseFloat($(this).val()) || 0;
        const diferencia = saldoNuevo - saldoActual;
        
        let mensaje = '';
        if (diferencia > 0) {
            mensaje = `Aumentará el saldo en $${diferencia.toFixed(2)}`;
        } else if (diferencia < 0) {
            mensaje = `Disminuirá el saldo en $${Math.abs(diferencia).toFixed(2)}`;
        } else {
            mensaje = 'No habrá cambios en el saldo';
        }
        
        // Mostrar mensaje de diferencia
        $('#diferencia_saldo').remove();
        if (diferencia !== 0) {
            $(this).after(`<small id="diferencia_saldo" class="form-text text-info">${mensaje}</small>`);
        }
    });
    
    // Validación del formulario de ajuste
    $('#ajustarSaldoForm').submit(function(e) {
        const motivo = $('#motivo_ajuste_saldo').val().trim();
        if (motivo.length < 5) {
            e.preventDefault();
            alert('El motivo debe tener al menos 5 caracteres');
            $('#motivo_ajuste_saldo').focus();
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

.table-secondary {
    background-color: rgba(108, 117, 125, 0.075);
}

.text-danger {
    font-weight: 600;
}

.text-success {
    font-weight: 600;
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

/* ✅ NUEVO: Mejorar visualización de cliente */
.cliente-nombre {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
}

.cliente-tipo-badge {
    display: inline-block;
    margin-top: 4px;
}

.cliente-direccion {
    color: #6c757d;
    font-size: 0.85rem;
    margin-top: 2px;
}
</style>
@endpush
@extends('layouts.app')

@section('title', 'Detalle del Cliente')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-user-circle text-primary"></i>
                        {{ $cliente->nombre }} {{ $cliente->apellido }}
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Cliente desde {{ $cliente->created_at->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            {{-- INFORMACIÓN PERSONAL --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Nombre:</dt>
                        <dd class="col-sm-9">{{ $cliente->nombre }} {{ $cliente->apellido }}</dd>

                        <dt class="col-sm-3">Teléfono:</dt>
                        <dd class="col-sm-9">{{ $cliente->telefono ?? 'No registrado' }}</dd>

                        <dt class="col-sm-3">Email:</dt>
                        <dd class="col-sm-9">{{ $cliente->email ?? 'No registrado' }}</dd>

                        <dt class="col-sm-3">Dirección:</dt>
                        <dd class="col-sm-9">{{ $cliente->direccion ?? 'No registrada' }}</dd>

                        <dt class="col-sm-3">Tipo:</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-secondary">{{ ucfirst($cliente->tipo_cliente ?? 'minorista') }}</span>
                        </dd>

                        <dt class="col-sm-3">Estado:</dt>
                        <dd class="col-sm-9">
                            @if($cliente->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- CUENTA CORRIENTE --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Cuenta Corriente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h4 class="{{ $cliente->saldo_cc >= 0 ? 'text-success' : 'text-danger' }}">
                                ${{ number_format(abs($cliente->saldo_cc), 2) }}
                            </h4>
                            <small>Saldo Actual</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-primary">
                                ${{ number_format($cliente->limite_credito, 2) }}
                            </h4>
                            <small>Límite de Crédito</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-success">
                                ${{ number_format($cliente->limite_credito + $cliente->saldo_cc, 2) }}
                            </h4>
                            <small>Crédito Disponible</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HISTORIAL DE COMPRAS --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Historial de Compras
                    </h5>
                </div>
                <div class="card-body">
                    @if($cliente->ventas && $cliente->ventas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Método</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->ventas as $venta)
                                <tr>
                                    <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                                    <td>${{ number_format($venta->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $venta->metodo_pago }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center text-muted">Sin compras registradas</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-7">Total Compras:</dt>
                        <dd class="col-5 text-end">${{ number_format($estadisticas['total_compras'], 2) }}</dd>

                        <dt class="col-7">Cantidad:</dt>
                        <dd class="col-5 text-end">{{ $estadisticas['cantidad_compras'] }}</dd>

                        <dt class="col-7">Promedio:</dt>
                        <dd class="col-5 text-end">${{ number_format($estadisticas['promedio_compra'], 2) }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Acciones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
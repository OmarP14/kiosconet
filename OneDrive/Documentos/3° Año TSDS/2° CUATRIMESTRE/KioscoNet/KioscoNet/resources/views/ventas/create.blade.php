{{-- ARCHIVO: resources/views/ventas/create.blade.php 
Sistema de Ventas MEJORADO - Versión con todos los RF implementados
--}}

@extends('layouts.app')

@section('title', 'Nueva Venta')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-cash-register me-2"></i>Nueva Venta
                        </h4>
                        <div>
                            <span class="badge bg-light text-dark me-2">
                                <i class="fas fa-keyboard me-1"></i>F9: Procesar | Ctrl+F: Buscar
                            </span>
                            <a href="{{ route('ventas.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Alertas -->
                    <div id="alert-container"></div>

                    <form id="ventaForm">
                        @csrf
                        
                        <!-- Información del Cliente y Lista de Precios -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user me-1"></i>Cliente *
                                </label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                                data-limite="{{ $cliente->limite_credito ?? 0 }}"
                                                data-saldo="{{ $cliente->saldo_cc ?? 0 }}"
                                                data-tipo="{{ $cliente->tipo_cliente ?? 'minorista' }}"
                                                {{ $cliente->id == 999 ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                                            @if($cliente->id == 999)
                                                <i class="fas fa-users"></i>
                                            @elseif($cliente->limite_credito > 0)
                                                <span class="badge bg-info">CC Habilitada</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    Por defecto se usa "Consumidor Final". Cambia solo si el cliente tiene cuenta.
                                </small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tags me-1"></i>Lista de Precios *
                                </label>
                                <select class="form-select" id="lista_precios" name="lista_precios" required>
                                    <option value="minorista">Minorista</option>
                                    <option value="mayorista">Mayorista</option>
                                    <option value="especial">Especial</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i>Fecha
                                </label>
                                <input type="date" class="form-control" name="fecha_venta" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <!-- Información de Cuenta Corriente -->
                        <div id="info-cuenta-corriente" class="row mb-4" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-info border-0 shadow-sm">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Saldo Actual:</strong>
                                            <span id="saldo-actual" class="text-danger fs-5">$0.00</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Límite de Crédito:</strong>
                                            <span id="limite-credito" class="text-success fs-5">$0.00</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Disponible:</strong>
                                            <span id="credito-disponible" class="text-info fs-5">$0.00</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Uso de Crédito:</strong>
                                            <div class="progress mt-1" style="height: 20px;">
                                                <div id="progress-credito" class="progress-bar" role="progressbar" 
                                                     style="width: 0%">0%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Búsqueda de Productos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-search me-1"></i>Buscar Producto
                                    <span class="badge bg-success ms-2" id="barcode-status" style="display: none;">
                                        <i class="fas fa-check me-1"></i>Lector de código de barras activo
                                    </span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-barcode" id="barcode-icon"></i>
                                    </span>
                                    <input type="text" id="buscar-producto" class="form-control"
                                           placeholder="Escanee código de barras o escriba nombre del producto..."
                                           autocomplete="off">
                                    <button class="btn btn-primary" type="button" id="btn-buscar">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Tip:</strong> Enfoque este campo y escanee el código de barras del producto. Se agregará automáticamente.
                                </small>
                                <div id="resultados-productos" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Tabla de Productos -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="30%">Producto</th>
                                        <th width="12%">Precio Unit.</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="12%">Descuento</th>
                                        <th width="15%">Subtotal</th>
                                        <th width="8%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-productos">
                                    <tr id="no-productos">
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
                                            <h5>No hay productos agregados</h5>
                                            <small>Use Ctrl+F para buscar productos rápidamente</small>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Subtotal:</th>
                                        <th id="subtotal-venta" class="text-dark">$0.00</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end">Descuento Global:</th>
                                        <th>
                                            <div class="input-group input-group-sm">
                                                <input type="number" id="descuento-global" class="form-control" 
                                                       value="0" min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end fs-5">TOTAL:</th>
                                        <th id="total-venta" class="text-primary fs-4 fw-bold">$0.00</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Método de Pago y Observaciones -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-credit-card me-1"></i>Método de Pago *
                                </label>
                                <select class="form-select form-select-lg" id="metodo_pago" name="metodo_pago" required>
                                    <option value="">Seleccionar método...</option>
                                    <option value="efectivo">💵 Efectivo</option>
                                    <option value="tarjeta">💳 Tarjeta Débito/Crédito</option>
                                    <option value="transferencia">🏦 Transferencia Bancaria</option>
                                    <option value="cuenta_corriente">📋 Cuenta Corriente</option>
                                    <option value="mixto">🔄 Pago Mixto</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-comment-dots me-1"></i>Observaciones
                                </label>
                                <textarea class="form-control" name="observaciones" rows="3" 
                                          placeholder="Observaciones adicionales sobre la venta..."></textarea>
                            </div>
                        </div>

                        <!-- Campos de Pago: EFECTIVO -->
                        <div id="campos-efectivo" class="payment-fields" style="display: none;">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Pago en Efectivo</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Monto Recibido *</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" class="form-control" 
                                                       id="monto_recibido" name="monto_recibido" 
                                                       placeholder="0.00" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Vuelto</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text">$</span>
                                                <input type="text" class="form-control bg-light fw-bold" 
                                                       id="vuelto" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Total a Pagar</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text">$</span>
                                                <input type="text" class="form-control bg-warning fw-bold" 
                                                       id="total-efectivo" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos de Pago: TARJETA -->
                        <div id="campos-tarjeta" class="payment-fields" style="display: none;">
                            <div class="card border-info mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i>Pago con Tarjeta</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Tipo de Tarjeta *</label>
                                            <select class="form-select" name="tipo_tarjeta" id="tipo_tarjeta">
                                                <option value="">Seleccionar...</option>
                                                <option value="debito" data-comision="2">Débito (2% comisión)</option>
                                                <option value="credito" data-comision="3.5">Crédito (3.5% comisión)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Últimos 4 dígitos *</label>
                                            <input type="text" class="form-control" name="ultimos_digitos" 
                                                   placeholder="1234" maxlength="4" pattern="[0-9]{4}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Código Autorización *</label>
                                            <input type="text" class="form-control" name="codigo_autorizacion" 
                                                   placeholder="ABC123456">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Comisión</label>
                                            <input type="text" class="form-control bg-light" id="comision-tarjeta" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Monto Final</label>
                                            <input type="text" class="form-control bg-warning fw-bold" id="total-tarjeta" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos de Pago: TRANSFERENCIA -->
                        <div id="campos-transferencia" class="payment-fields" style="display: none;">
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Transferencia Bancaria</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Número Transferencia *</label>
                                            <input type="text" class="form-control" name="numero_transferencia" 
                                                   placeholder="TRF123456789">
                                        </div>
                                        <div class="col-md-3">
                                                <label class="form-label">Banco *</label>
                                                <select class="form-select" name="banco">
                                                    <option value="">Seleccionar banco...</option>
                                                    <option value="Banco de la Nación Argentina">Banco de la Nación Argentina</option>
                                                    <option value="Banco Galicia">Banco de Galicia y Buenos Aires S.A.U.</option>
                                                    <option value="Banco BBVA Argentina">Banco BBVA Argentina</option>
                                                    <option value="Banco Supervielle">Banco Supervielle S.A.</option>
                                                    <option value="Banco Hipotecario">Banco Hipotecario S.A.</option>
                                                    <option value="Banco de San Juan">Banco de San Juan S.A.</option>
                                                    <option value="HSBC Bank Argentina">HSBC Bank Argentina</option>
                                                    <option value="Banco Credicoop">Banco Credicoop Cooperativo Limitado</option>
                                                    <option value="Banco Macro">Banco Macro S.A.</option>
                                                    <option value="Banco Santander Argentina">Banco Santander Río</option>
                                                    <option value="Banco Patagonia">Banco Patagonia S.A.</option>
                                                    <option value="Banco Ciudad">Banco Ciudad de Buenos Aires</option>
                                                    <option value="Banco Provincia">Banco de la Provincia de Buenos Aires</option>
                                                    <option value="Otro">Otro Banco</option>
                                                </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Fecha Transferencia *</label>
                                            <input type="datetime-local" class="form-control" name="fecha_transferencia" id="fecha_transferencia">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Monto</label>
                                            <input type="text" class="form-control bg-warning fw-bold" 
                                                   id="total-transferencia" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Campos de Pago: PAGO MIXTO -->
                        <div id="campos-mixto" class="payment-fields" style="display: none;">
                            <div class="card mb-3" style="border: 3px solid #6f42c1;">
                                <div class="card-header text-white" style="background-color: #6f42c1;">
                                    <h6 class="mb-0"><i class="fas fa-coins me-2"></i>Pago Mixto - Combine múltiples métodos</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Total a pagar:</strong> <span id="total-mixto-info" class="fs-5 fw-bold">$0.00</span>
                                        <span class="ms-4"><strong>Pagado:</strong> <span id="pagado-mixto" class="text-success">$0.00</span></span>
                                        <span class="ms-4"><strong>Falta:</strong> <span id="falta-mixto" class="text-danger">$0.00</span></span>
                                    </div>

                                    <!-- EFECTIVO -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mixto-efectivo" onchange="toggleMetodoMixto('efectivo')">
                                                <label class="form-check-label fw-bold" for="mixto-efectivo">
                                                    💵 Efectivo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="mixto-efectivo-campos" style="display: none;" class="bg-light p-3 rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Monto en Efectivo</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" class="form-control" id="mixto-efectivo-monto" 
                                                        placeholder="0.00" min="0" onchange="calcularPagoMixto()">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Vuelto</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control bg-white fw-bold" id="mixto-efectivo-vuelto" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TARJETA -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mixto-tarjeta" onchange="toggleMetodoMixto('tarjeta')">
                                                <label class="form-check-label fw-bold" for="mixto-tarjeta">
                                                    💳 Tarjeta
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="mixto-tarjeta-campos" style="display: none;" class="bg-light p-3 rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Monto Tarjeta</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" class="form-control" id="mixto-tarjeta-monto" 
                                                        placeholder="0.00" min="0" onchange="calcularPagoMixto()">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Tipo</label>
                                                <select class="form-select" id="mixto-tipo-tarjeta">
                                                    <option value="">Seleccionar...</option>
                                                    <option value="debito">Débito</option>
                                                    <option value="credito">Crédito</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Últimos 4 dígitos</label>
                                                <input type="text" class="form-control" id="mixto-ultimos-digitos" 
                                                    placeholder="1234" maxlength="4">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Cód. Autorización</label>
                                                <input type="text" class="form-control" id="mixto-codigo-autorizacion" 
                                                    placeholder="ABC123">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TRANSFERENCIA -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mixto-transferencia" onchange="toggleMetodoMixto('transferencia')">
                                                <label class="form-check-label fw-bold" for="mixto-transferencia">
                                                    🏦 Transferencia
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="mixto-transferencia-campos" style="display: none;" class="bg-light p-3 rounded mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Monto Transferencia</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" class="form-control" id="mixto-transferencia-monto" 
                                                        placeholder="0.00" min="0" onchange="calcularPagoMixto()">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Número Transferencia</label>
                                                <input type="text" class="form-control" id="mixto-numero-transferencia" 
                                                    placeholder="TRF123456">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Banco</label>
                                                <select class="form-select" id="mixto-banco">
                                                    <option value="">Seleccionar...</option>
                                                    <option value="Banco de la Nación Argentina">Banco Nación</option>
                                                    <option value="Banco Galicia">Banco Galicia</option>
                                                    <option value="Banco BBVA Argentina">BBVA</option>
                                                    <option value="Banco Macro">Banco Macro</option>
                                                    <option value="Otro">Otro</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Fecha</label>
                                                <input type="datetime-local" class="form-control" id="mixto-fecha-transferencia">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Campos de Pago: CUENTA CORRIENTE -->
                        <div id="campos-cuenta-corriente" class="payment-fields" style="display: none;">
                            <div class="card border-primary mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Cuenta Corriente</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Saldo Anterior</label>
                                            <input type="text" class="form-control bg-light" id="saldo-anterior-cc" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Monto a Cargar</label>
                                            <input type="text" class="form-control bg-light" id="monto-cc" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Nuevo Saldo</label>
                                            <input type="text" class="form-control bg-light fw-bold" id="nuevo-saldo-cc" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Estado</label>
                                            <input type="text" class="form-control fw-bold" id="estado-cc" readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label class="form-label">Observaciones Cuenta Corriente</label>
                                            <textarea class="form-control" name="observaciones_cc" rows="2" 
                                                      placeholder="Observaciones específicas para cuenta corriente..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-secondary btn-lg" onclick="window.history.back()">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </button>
                                    
                                    <div>
                                        <button type="button" class="btn btn-info btn-lg me-2" id="btn-resumen">
                                            <i class="fas fa-eye me-2"></i>Ver Resumen
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg px-5" id="btn-procesar">
                                            <i class="fas fa-cash-register me-2"></i>Procesar Venta (F9)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Resumen de Venta -->
<div class="modal fade" id="modalResumen" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice me-2"></i>Resumen de Venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido-resumen">
                <!-- Se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Venta Procesada
                </h5>
            </div>
            <div class="modal-body text-center py-5">
                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                <h3 class="mt-4">¡Venta registrada exitosamente!</h3>
                <p class="text-muted mb-4">La venta ha sido procesada correctamente.</p>
                <div id="info-venta-procesada" class="alert alert-info">
                    <!-- Se llenará dinámicamente -->
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary btn-lg" id="btn-imprimir-ticket">
                    <i class="fas fa-print me-2"></i>Imprimir Ticket
                </button>
                <button type="button" class="btn btn-secondary btn-lg" id="btn-descargar-pdf">
                    <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                </button>
                <button type="button" class="btn btn-success btn-lg" onclick="window.location.reload()">
                    <i class="fas fa-plus me-2"></i>Nueva Venta
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('ventas.index') }}'">
                    <i class="fas fa-list me-2"></i>Ver Ventas
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ==================== ESTILOS PERSONALIZADOS ==================== */

/* Gradiente en header */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Animaciones */
.payment-fields {
    margin-bottom: 1rem;
    animation: slideDown 0.3s ease-in-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Productos en búsqueda */
.producto-resultado {
    cursor: pointer;
    transition: all 0.3s;
    border-left: 4px solid transparent;
}

.producto-resultado:hover {
    background-color: var(--bs-primary);
    color: white;
    transform: translateX(5px);
    border-left-color: var(--bs-warning);
}

.producto-resultado.stock-bajo {
    border-left-color: var(--bs-warning);
}

.producto-resultado.stock-critico {
    border-left-color: var(--bs-danger);
}

.producto-resultado.proximo-vencer {
    background-color: #fff3cd;
}

/* Tabla */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

#tabla-productos tbody tr {
    animation: slideIn 0.3s ease-in-out;
}

/* Cards */
.card {
    border-radius: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

/* Botones */
.btn {
    border-radius: 10px;
    transition: all 0.3s;
    font-weight: 600;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Alertas */
.alert {
    border-radius: 12px;
    animation: fadeIn 0.5s ease-in-out;
    border: none;
}

/* Inputs */
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    transform: translateY(-1px);
}

/* Badge para atajos */
.badge {
    font-weight: 500;
    padding: 0.5em 0.8em;
}

/* Progress bar para crédito */
.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
}

/* Resultados de búsqueda */
#resultados-productos {
    max-height: 400px;
    overflow-y: auto;
    border-radius: 10px;
}

#resultados-productos::-webkit-scrollbar {
    width: 8px;
}

#resultados-productos::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#resultados-productos::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

#resultados-productos::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Input de cantidad */
.input-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

/* Modal */
.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

/* Responsive */
@media (max-width: 768px) {
    .table {
        font-size: 0.875rem;
    }
    
    .btn-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
}

/* Estados visuales */
.border-success-thick {
    border: 3px solid var(--bs-success) !important;
}

.border-danger-thick {
    border: 3px solid var(--bs-danger) !important;
}

.border-warning-thick {
    border: 3px solid var(--bs-warning) !important;
}

/* Loading spinner */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // VARIABLES GLOBALES
    // ==========================================
    let productosVenta = [];
    let totalVenta = 0;
    let subtotalVenta = 0;
    let descuentoGlobal = 0;
    let clienteSeleccionado = null;
    let ventaProcesadaId = null;
    let comisionActual = 0;

    // Referencias DOM
    const clienteSelect = document.getElementById('cliente_id');
    const listaPreciosSelect = document.getElementById('lista_precios');
    const metodoSelect = document.getElementById('metodo_pago');
    const buscarInput = document.getElementById('buscar-producto');
    const tablaProductos = document.getElementById('tabla-productos');
    const totalElement = document.getElementById('total-venta');
    const subtotalElement = document.getElementById('subtotal-venta');
    const descuentoGlobalInput = document.getElementById('descuento-global');
    const ventaForm = document.getElementById('ventaForm');

    // ==========================================
    // EVENT LISTENERS
    // ==========================================
    clienteSelect.addEventListener('change', actualizarInfoCliente);
    listaPreciosSelect.addEventListener('change', actualizarPreciosProductos);
    metodoSelect.addEventListener('change', mostrarCamposPago);
    buscarInput.addEventListener('input', debounce(buscarProductos, 300));
    descuentoGlobalInput.addEventListener('input', actualizarTablaProductos);
    document.getElementById('btn-buscar').addEventListener('click', buscarProductos);
    document.getElementById('btn-resumen').addEventListener('click', mostrarResumenVenta);

    // ==========================================
    // DETECCIÓN DE CÓDIGO DE BARRAS
    // ==========================================
    let barcodeBuffer = '';
    let barcodeTimeout = null;

    buscarInput.addEventListener('keypress', function(e) {
        // Los lectores de código de barras escriben muy rápido y terminan con Enter
        if (e.key === 'Enter') {
            e.preventDefault();

            const codigo = buscarInput.value.trim();

            // Si el valor fue escrito muy rápido (< 100ms por carácter), probablemente sea un escáner
            if (codigo.length > 3) {
                buscarPorCodigoBarras(codigo);
            } else {
                buscarProductos();
            }
        }
    });

    // Detectar entrada rápida (típico de lectores de código de barras)
    buscarInput.addEventListener('input', function(e) {
        const currentTime = new Date().getTime();

        if (barcodeTimeout) {
            clearTimeout(barcodeTimeout);
        }

        // Si el texto fue escrito muy rápido, activar indicador visual
        barcodeTimeout = setTimeout(function() {
            if (buscarInput.value.length >= 8) {
                mostrarIndicadorEscaneo(true);
            }
        }, 50);
    });
    
    const montoRecibidoInput = document.getElementById('monto_recibido');
    if (montoRecibidoInput) {
        montoRecibidoInput.addEventListener('input', calcularVuelto);
    }

    const tipoTarjetaSelect = document.getElementById('tipo_tarjeta');
    if (tipoTarjetaSelect) {
        tipoTarjetaSelect.addEventListener('change', calcularComisionTarjeta);
    }
    
    ventaForm.addEventListener('submit', procesarVenta);

    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            buscarInput.focus();
            buscarInput.select();
        }
        
        if (e.key === 'F9') {
            e.preventDefault();
            if (productosVenta.length > 0) {
                document.getElementById('btn-procesar').click();
            }
        }

        if (e.key === 'Escape' && document.activeElement === buscarInput) {
            buscarInput.value = '';
            document.getElementById('resultados-productos').innerHTML = '';
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (productosVenta.length > 0) {
            e.preventDefault();
            e.returnValue = '¿Está seguro que desea salir? Se perderán los productos agregados.';
        }
    });

    // ==========================================
    // FUNCIONES DE UTILIDAD
    // ==========================================
    function mostrarAlerta(mensaje, tipo = 'success') {
        const alertContainer = document.getElementById('alert-container');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertContainer.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), wait);
        };
    }

    function formatearMoneda(valor) {
        return new Intl.NumberFormat('es-AR', {
            style: 'currency',
            currency: 'ARS'
        }).format(valor);
    }

    // ==========================================
    // FUNCIONES DE CLIENTE
    // ==========================================
    function actualizarInfoCliente() {
        const selectedOption = clienteSelect.options[clienteSelect.selectedIndex];

        if (!selectedOption.value) {
            document.getElementById('info-cuenta-corriente').style.display = 'none';
            clienteSeleccionado = null;
            return;
        }

        clienteSeleccionado = {
            id: selectedOption.value,
            limite: parseFloat(selectedOption.dataset.limite || 0),
            saldo: parseFloat(selectedOption.dataset.saldo || 0),
            tipo: selectedOption.dataset.tipo || 'minorista'
        };

        listaPreciosSelect.value = clienteSeleccionado.tipo;

        // Ocultar info de CC para Consumidor Final (ID 999)
        if (clienteSeleccionado.id == '999') {
            document.getElementById('info-cuenta-corriente').style.display = 'none';
            return;
        }

        const disponible = clienteSeleccionado.limite - clienteSeleccionado.saldo;
        const porcentajeUso = clienteSeleccionado.limite > 0
            ? (clienteSeleccionado.saldo / clienteSeleccionado.limite) * 100
            : 0;

        document.getElementById('saldo-actual').textContent = formatearMoneda(clienteSeleccionado.saldo);
        document.getElementById('limite-credito').textContent = formatearMoneda(clienteSeleccionado.limite);
        document.getElementById('credito-disponible').textContent = formatearMoneda(disponible);

        const progressBar = document.getElementById('progress-credito');
        progressBar.style.width = porcentajeUso + '%';
        progressBar.textContent = Math.round(porcentajeUso) + '%';

        progressBar.className = 'progress-bar';
        if (porcentajeUso < 50) {
            progressBar.classList.add('bg-success');
        } else if (porcentajeUso < 80) {
            progressBar.classList.add('bg-warning');
        } else {
            progressBar.classList.add('bg-danger');
        }

        if (clienteSeleccionado.limite > 0) {
            document.getElementById('info-cuenta-corriente').style.display = 'block';
        }
    }

    // ==========================================
    // FUNCIONES DE BÚSQUEDA DE PRODUCTOS
    // ==========================================
    async function buscarProductos() {
        const termino = buscarInput.value.trim();
        const resultadosDiv = document.getElementById('resultados-productos');
        const listaPrecio = listaPreciosSelect.value;

        if (termino.length < 2) {
            resultadosDiv.innerHTML = '';
            return;
        }

        resultadosDiv.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><br><small>Buscando productos...</small></div>';

        try {
            const response = await fetch(`/api/productos/buscar?termino=${encodeURIComponent(termino)}&lista_precio=${listaPrecio}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                }
            });

            const data = await response.json();

            if (!data.success) {
                resultadosDiv.innerHTML = `<div class="alert alert-warning mb-0">
                    <i class="fas fa-search me-2"></i>${data.message || 'No se encontraron productos'}
                </div>`;
                return;
            }

            const productos = data.data || [];

            if (productos.length > 0) {
                let html = '<div class="list-group shadow-sm">';
                productos.forEach(producto => {
                    const disabled = producto.stock === 0;
                    const stockBajo = producto.stock > 0 && producto.stock <= (producto.stock_minimo || 10);
                    const stockCritico = producto.stock > 0 && producto.stock <= 5;
                    const proximoVencer = producto.dias_hasta_vencimiento && producto.dias_hasta_vencimiento <= 30;
                    
                    let claseAdicional = '';
                    let badgeStock = '';
                    let alertaVencimiento = '';
                    
                    if (disabled) {
                        claseAdicional = 'disabled';
                        badgeStock = '<span class="badge bg-danger">Sin Stock</span>';
                    } else if (stockCritico) {
                        claseAdicional = 'stock-critico';
                        badgeStock = '<span class="badge bg-danger">Stock Crítico</span>';
                    } else if (stockBajo) {
                        claseAdicional = 'stock-bajo';
                        badgeStock = '<span class="badge bg-warning">Stock Bajo</span>';
                    }
                    
                    if (proximoVencer) {
                        claseAdicional += ' proximo-vencer';
                        alertaVencimiento = `<span class="badge bg-warning text-dark">
                            <i class="fas fa-exclamation-triangle me-1"></i>Vence en ${producto.dias_hasta_vencimiento} días
                        </span>`;
                    }
                    
                    html += `
                        <div class="list-group-item producto-resultado ${claseAdicional}" 
                             ${!disabled ? `onclick="agregarProducto(${producto.id}, '${producto.nombre.replace(/'/g, "\\'")}', ${producto.precio}, ${producto.stock}, ${producto.precio_costo || 0})"` : ''}>
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <strong class="me-2">${producto.nombre}</strong>
                                        ${badgeStock}
                                        ${alertaVencimiento}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-box me-1"></i>Stock: ${producto.stock}
                                        <span class="ms-3"><i class="fas fa-tag me-1"></i>${formatearMoneda(producto.precio)}</span>
                                        ${producto.codigo_barra ? `<span class="ms-3"><i class="fas fa-barcode me-1"></i>${producto.codigo_barra}</span>` : ''}
                                    </div>
                                </div>
                                ${!disabled ? '<span class="badge bg-primary rounded-pill fs-6">Agregar <i class="fas fa-plus ms-1"></i></span>' : ''}
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                resultadosDiv.innerHTML = html;
            } else {
                resultadosDiv.innerHTML = '<div class="alert alert-info mb-0"><i class="fas fa-info-circle me-2"></i>No se encontraron productos</div>';
            }

        } catch (error) {
            console.error('Error:', error);
            resultadosDiv.innerHTML = `<div class="alert alert-danger mb-0">
                <i class="fas fa-exclamation-circle me-2"></i>Error al buscar productos: ${error.message}
            </div>`;
        }
    }

    // ==========================================
    // BÚSQUEDA POR CÓDIGO DE BARRAS
    // ==========================================
    async function buscarPorCodigoBarras(codigo) {
        const resultadosDiv = document.getElementById('resultados-productos');
        const listaPrecio = listaPreciosSelect.value;

        try {
            mostrarIndicadorEscaneo(true);

            // Mostrar indicador de carga
            resultadosDiv.innerHTML = `<div class="alert alert-info mb-0">
                <i class="fas fa-barcode me-2"></i>Buscando producto con código: <strong>${codigo}</strong>...
            </div>`;

            const response = await fetch(`/api/productos/buscar?termino=${encodeURIComponent(codigo)}&lista_precio=${listaPrecio}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success && data.data && data.data.length > 0) {
                // Encontró producto(s)
                const productos = data.data;

                // Si hay exactamente un producto, agregarlo directamente
                if (productos.length === 1) {
                    const producto = productos[0];

                    // Agregar directamente al carrito
                    agregarProducto(
                        producto.id,
                        producto.nombre,
                        producto.precio,
                        producto.stock,
                        producto.precio_costo
                    );

                    // Limpiar campo de búsqueda
                    buscarInput.value = '';
                    buscarInput.focus();

                    // Limpiar resultados
                    resultadosDiv.innerHTML = '';

                    // Mostrar alerta de éxito con sonido
                    mostrarAlerta(`✓ ${producto.nombre} agregado al carrito`, 'success');
                    reproducirSonidoExito();
                } else {
                    // Si hay múltiples coincidencias, mostrarlas
                    mostrarResultadosProductos(productos, resultadosDiv);
                }
            } else {
                // No encontró el producto
                resultadosDiv.innerHTML = `<div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Producto no encontrado</strong><br>
                    No se encontró ningún producto con el código: <strong>${codigo}</strong>
                </div>`;
                reproducirSonidoError();

                // Limpiar después de 3 segundos
                setTimeout(() => {
                    resultadosDiv.innerHTML = '';
                    buscarInput.value = '';
                }, 3000);
            }

            mostrarIndicadorEscaneo(false);

        } catch (error) {
            console.error('Error al buscar por código de barras:', error);
            resultadosDiv.innerHTML = `<div class="alert alert-danger mb-0">
                <i class="fas fa-exclamation-circle me-2"></i>Error al buscar producto: ${error.message}
            </div>`;
            mostrarIndicadorEscaneo(false);
            reproducirSonidoError();
        }
    }

    // Mostrar resultados de productos (cuando hay múltiples coincidencias)
    function mostrarResultadosProductos(productos, resultadosDiv) {
        let html = '<div class="list-group">';

        productos.forEach(producto => {
            const stockClass = producto.stock > 0 ? 'text-success' : 'text-danger';
            const stockIcon = producto.stock > 0 ? 'fa-check-circle' : 'fa-times-circle';

            html += `
                <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                        onclick="agregarProducto(${producto.id}, '${producto.nombre.replace(/'/g, "\\'")}', ${producto.precio}, ${producto.stock}, ${producto.precio_costo || 0})">
                    <div>
                        <h6 class="mb-1">${producto.nombre}</h6>
                        <small class="text-muted">Código: ${producto.codigo || 'N/A'}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">${formatearMoneda(producto.precio)}</div>
                        <small class="${stockClass}">
                            <i class="fas ${stockIcon}"></i> Stock: ${producto.stock}
                        </small>
                    </div>
                </button>
            `;
        });

        html += '</div>';
        resultadosDiv.innerHTML = html;
    }

    // Mostrar indicador visual de escaneo
    function mostrarIndicadorEscaneo(activo) {
        const statusBadge = document.getElementById('barcode-status');
        const barcodeIcon = document.getElementById('barcode-icon');

        if (activo) {
            if (statusBadge) statusBadge.style.display = 'inline-block';
            if (barcodeIcon) barcodeIcon.classList.add('fa-spin');
        } else {
            if (statusBadge) {
                setTimeout(() => {
                    statusBadge.style.display = 'none';
                }, 2000);
            }
            if (barcodeIcon) barcodeIcon.classList.remove('fa-spin');
        }
    }

    // Reproducir sonido de éxito (opcional)
    function reproducirSonidoExito() {
        // Crear un sonido simple con Web Audio API
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 800;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        } catch (e) {
            console.log('Audio no disponible');
        }
    }

    // Reproducir sonido de error (opcional)
    function reproducirSonidoError() {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 200;
            oscillator.type = 'sawtooth';

            gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.2);
        } catch (e) {
            console.log('Audio no disponible');
        }
    }

    // ==========================================
    // FUNCIONES DE PRODUCTOS EN VENTA
    // ==========================================
    window.agregarProducto = function(id, nombre, precio, stock, precioCosto = 0) {
        const productoExistente = productosVenta.find(p => p.id == id);
        
        if (productoExistente) {
            if (productoExistente.cantidad < stock) {
                productoExistente.cantidad++;
                productoExistente.subtotal = productoExistente.cantidad * productoExistente.precio;
                mostrarAlerta(`Cantidad actualizada: ${productoExistente.cantidad}`, 'info');
            } else {
                mostrarAlerta('No hay suficiente stock disponible', 'warning');
                return;
            }
        } else {
            productosVenta.push({
                id: id,
                nombre: nombre,
                precio: parseFloat(precio),
                precio_costo: parseFloat(precioCosto),
                cantidad: 1,
                descuento: 0,
                subtotal: parseFloat(precio),
                stock: stock
            });
            mostrarAlerta(`Producto agregado: ${nombre}`, 'success');
        }

        actualizarTablaProductos();
        document.getElementById('resultados-productos').innerHTML = '';
        buscarInput.value = '';
        buscarInput.focus();
    };

    function actualizarPreciosProductos() {
        if (productosVenta.length > 0) {
            mostrarAlerta('Los precios se actualizarán al volver a agregar productos', 'info');
        }
    }

    function actualizarTablaProductos() {
        if (productosVenta.length === 0) {
            tablaProductos.innerHTML = `
                <tr id="no-productos">
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
                        <h5>No hay productos agregados</h5>
                        <small class="text-muted">Use Ctrl+F para buscar productos rápidamente</small>
                    </td>
                </tr>
            `;
            subtotalVenta = 0;
            totalVenta = 0;
        } else {
            let html = '';
            subtotalVenta = 0;
            
            productosVenta.forEach((producto, index) => {
                const descuentoProd = producto.precio * (producto.descuento / 100);
                const precioConDesc = producto.precio - descuentoProd;
                producto.subtotal = producto.cantidad * precioConDesc;
                subtotalVenta += producto.subtotal;
                
                html += `
                    <tr>
                        <td>
                            <strong>${producto.nombre}</strong>
                            <br><small class="text-muted">Stock disponible: ${producto.stock}</small>
                        </td>
                        <td>${formatearMoneda(producto.precio)}</td>
                        <td>
                            <div class="input-group input-group-sm" style="width: 140px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${index}, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center fw-bold" value="${producto.cantidad}" 
                                       onchange="actualizarCantidad(${index}, this.value)" min="1" max="${producto.stock}">
                                <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${index}, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <input type="number" class="form-control text-center" value="${producto.descuento}" 
                                       onchange="actualizarDescuento(${index}, this.value)" min="0" max="100" step="0.01">
                                <span class="input-group-text">%</span>
                            </div>
                        </td>
                        <td class="fw-bold">${formatearMoneda(producto.subtotal)}</td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm" type="button" onclick="eliminarProducto(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            tablaProductos.innerHTML = html;
        }

        descuentoGlobal = parseFloat(descuentoGlobalInput.value) || 0;
        const montoDescuentoGlobal = subtotalVenta * (descuentoGlobal / 100);
        totalVenta = subtotalVenta - montoDescuentoGlobal;

        subtotalElement.textContent = formatearMoneda(subtotalVenta);
        totalElement.textContent = formatearMoneda(totalVenta);
        
        actualizarCamposPagoTotales();
        
        if (metodoSelect.value === 'cuenta_corriente') {
            actualizarCamposCuentaCorriente();
        }
        
        // ✅ ACTUALIZAR CAMPOS DE PAGO MIXTO
        if (metodoSelect.value === 'mixto') {
            document.getElementById('total-mixto-info').textContent = formatearMoneda(totalVenta);
            calcularPagoMixto();
        }
    }

    window.cambiarCantidad = function(index, cambio) {
        const producto = productosVenta[index];
        const nuevaCantidad = producto.cantidad + cambio;
        
        if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stock) {
            producto.cantidad = nuevaCantidad;
            actualizarTablaProductos();
        } else if (nuevaCantidad > producto.stock) {
            mostrarAlerta('No hay suficiente stock disponible', 'warning');
        }
    };

    window.actualizarCantidad = function(index, nuevaCantidad) {
        nuevaCantidad = parseInt(nuevaCantidad);
        const producto = productosVenta[index];
        
        if (nuevaCantidad >= 1 && nuevaCantidad <= producto.stock) {
            producto.cantidad = nuevaCantidad;
            actualizarTablaProductos();
        } else {
            mostrarAlerta('Cantidad no válida', 'warning');
            actualizarTablaProductos();
        }
    };

    window.actualizarDescuento = function(index, nuevoDescuento) {
        nuevoDescuento = parseFloat(nuevoDescuento) || 0;
        if (nuevoDescuento >= 0 && nuevoDescuento <= 100) {
            productosVenta[index].descuento = nuevoDescuento;
            actualizarTablaProductos();
        }
    };

    window.eliminarProducto = function(index) {
        const producto = productosVenta[index];
        if (confirm(`¿Eliminar ${producto.nombre} de la venta?`)) {
            productosVenta.splice(index, 1);
            actualizarTablaProductos();
            mostrarAlerta('Producto eliminado', 'info');
        }
    };

    // ==========================================
    // FUNCIONES DE MÉTODOS DE PAGO
    // ==========================================
    function mostrarCamposPago() {
        document.querySelectorAll('.payment-fields').forEach(field => {
            field.style.display = 'none';
        });

        const metodo = metodoSelect.value;
        
        if (metodo) {
            const camposPago = document.getElementById(`campos-${metodo.replace('_', '-')}`);
            if (camposPago) {
                camposPago.style.display = 'block';
                actualizarCamposPagoTotales();
                
                if (metodo === 'cuenta_corriente') {
                    actualizarCamposCuentaCorriente();
                } else if (metodo === 'transferencia') {
                    const ahora = new Date().toISOString().slice(0, 16);
                    document.querySelector('[name="fecha_transferencia"]').value = ahora;
                } else if (metodo === 'mixto') {
                    document.getElementById('total-mixto-info').textContent = formatearMoneda(totalVenta);
                    calcularPagoMixto();
                }
            }
        }
    }

    function actualizarCamposPagoTotales() {
        const totalEfectivo = document.getElementById('total-efectivo');
        const totalTarjeta = document.getElementById('total-tarjeta');
        const totalTransferencia = document.getElementById('total-transferencia');
        
        if (totalEfectivo) totalEfectivo.value = formatearMoneda(totalVenta);
        if (totalTarjeta) totalTarjeta.value = formatearMoneda(totalVenta);
        if (totalTransferencia) totalTransferencia.value = formatearMoneda(totalVenta);
    }

    function actualizarCamposCuentaCorriente() {
        if (clienteSeleccionado) {
            const saldoAnterior = clienteSeleccionado.saldo;
            const nuevoSaldo = saldoAnterior + totalVenta;
            const disponible = clienteSeleccionado.limite - nuevoSaldo;
            
            document.getElementById('saldo-anterior-cc').value = formatearMoneda(saldoAnterior);
            document.getElementById('monto-cc').value = formatearMoneda(totalVenta);
            document.getElementById('nuevo-saldo-cc').value = formatearMoneda(nuevoSaldo);
            
            const estadoInput = document.getElementById('estado-cc');
            if (disponible >= 0) {
                estadoInput.value = '✓ APROBADO';
                estadoInput.className = 'form-control fw-bold text-success border-success-thick';
            } else {
                estadoInput.value = '✗ LÍMITE EXCEDIDO';
                estadoInput.className = 'form-control fw-bold text-danger border-danger-thick';
            }
        }
    }

    function calcularVuelto() {
        const montoRecibido = parseFloat(document.getElementById('monto_recibido').value) || 0;
        const vuelto = montoRecibido - totalVenta;
        
        const vueltoInput = document.getElementById('vuelto');
        if (vueltoInput) {
            if (vuelto >= 0) {
                vueltoInput.value = formatearMoneda(vuelto);
                vueltoInput.className = 'form-control bg-light fw-bold text-success border-success-thick';
            } else {
                vueltoInput.value = formatearMoneda(0);
                vueltoInput.className = 'form-control bg-light fw-bold text-danger border-danger-thick';
            }
        }
        
        const montoInput = document.getElementById('monto_recibido');
        if (montoRecibido >= totalVenta) {
            montoInput.className = 'form-control border-success-thick';
        } else {
            montoInput.className = 'form-control border-danger-thick';
        }
    }

    function calcularComisionTarjeta() {
        const tipoTarjeta = document.getElementById('tipo_tarjeta');
        const selectedOption = tipoTarjeta.options[tipoTarjeta.selectedIndex];
        const porcentajeComision = parseFloat(selectedOption.dataset.comision || 0);
        
        comisionActual = totalVenta * (porcentajeComision / 100);
        
        const comisionInput = document.getElementById('comision-tarjeta');
        if (comisionInput) {
            comisionInput.value = `${formatearMoneda(comisionActual)} (${porcentajeComision}%)`;
        }
    }

    // ==========================================
    // FUNCIONES DE PAGO MIXTO
    // ==========================================
    window.toggleMetodoMixto = function(metodo) {
        const checkbox = document.getElementById(`mixto-${metodo}`);
        const campos = document.getElementById(`mixto-${metodo}-campos`);
        
        if (checkbox.checked) {
            campos.style.display = 'block';
            if (metodo === 'transferencia') {
                const ahora = new Date().toISOString().slice(0, 16);
                document.getElementById('mixto-fecha-transferencia').value = ahora;
            }
        } else {
            campos.style.display = 'none';
            const inputs = campos.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.type === 'number' || input.type === 'text' || input.type === 'datetime-local' || input.tagName === 'SELECT') {
                    input.value = '';
                }
            });
            calcularPagoMixto();
        }
    };

    window.calcularPagoMixto = function() {
        let totalPagado = 0;
        
        if (document.getElementById('mixto-efectivo').checked) {
            totalPagado += parseFloat(document.getElementById('mixto-efectivo-monto').value) || 0;
        }
        
        if (document.getElementById('mixto-tarjeta').checked) {
            totalPagado += parseFloat(document.getElementById('mixto-tarjeta-monto').value) || 0;
        }
        
        if (document.getElementById('mixto-transferencia').checked) {
            totalPagado += parseFloat(document.getElementById('mixto-transferencia-monto').value) || 0;
        }
        
        const falta = totalVenta - totalPagado;
        
        document.getElementById('total-mixto-info').textContent = formatearMoneda(totalVenta);
        document.getElementById('pagado-mixto').textContent = formatearMoneda(totalPagado);
        document.getElementById('falta-mixto').textContent = formatearMoneda(Math.max(0, falta));
        
        const faltaElement = document.getElementById('falta-mixto');
        if (falta <= 0) {
            faltaElement.className = 'fs-5 fw-bold text-success';
        } else {
            faltaElement.className = 'fs-5 fw-bold text-danger';
        }
        
        if (totalPagado > totalVenta && document.getElementById('mixto-efectivo').checked) {
            const vuelto = totalPagado - totalVenta;
            document.getElementById('mixto-efectivo-vuelto').value = formatearMoneda(vuelto);
        } else {
            document.getElementById('mixto-efectivo-vuelto').value = formatearMoneda(0);
        }
        
        return { totalPagado, falta };
    };

    // ==========================================
    // FUNCIÓN: MOSTRAR RESUMEN
    // ==========================================
    function mostrarResumenVenta() {
        if (productosVenta.length === 0) {
            mostrarAlerta('Agregue productos para ver el resumen', 'warning');
            return;
        }

        let htmlResumen = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary"><i class="fas fa-user me-2"></i>Cliente</h6>
                    <p class="mb-3">${clienteSelect.options[clienteSelect.selectedIndex].text}</p>
                    
                    <h6 class="text-primary"><i class="fas fa-tags me-2"></i>Lista de Precios</h6>
                    <p class="mb-3">${listaPreciosSelect.options[listaPreciosSelect.selectedIndex].text}</p>
                    
                    <h6 class="text-primary"><i class="fas fa-credit-card me-2"></i>Método de Pago</h6>
                    <p class="mb-3">${metodoSelect.options[metodoSelect.selectedIndex].text}</p>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="text-primary">Resumen de Importes</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">${formatearMoneda(subtotalVenta)}</td>
                                </tr>
                                <tr>
                                    <td>Descuento Global (${descuentoGlobal}%):</td>
                                    <td class="text-end text-danger">-${formatearMoneda(subtotalVenta * descuentoGlobal / 100)}</td>
                                </tr>
                                ${comisionActual > 0 ? `
                                <tr>
                                    <td>Comisión Tarjeta:</td>
                                    <td class="text-end text-warning">+${formatearMoneda(comisionActual)}</td>
                                </tr>
                                ` : ''}
                                <tr class="border-top">
                                    <td class="fw-bold fs-5">TOTAL:</td>
                                    <td class="text-end fw-bold fs-5 text-primary">${formatearMoneda(totalVenta)}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <h6 class="text-primary"><i class="fas fa-shopping-cart me-2"></i>Productos (${productosVenta.length})</h6>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end">Precio</th>
                            <th class="text-center">Desc.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        productosVenta.forEach(producto => {
            htmlResumen += `
                <tr>
                    <td>${producto.nombre}</td>
                    <td class="text-center">${producto.cantidad}</td>
                    <td class="text-end">${formatearMoneda(producto.precio)}</td>
                    <td class="text-center">${producto.descuento}%</td>
                    <td class="text-end fw-bold">${formatearMoneda(producto.subtotal)}</td>
                </tr>
            `;
        });

        htmlResumen += `
                    </tbody>
                </table>
            </div>
        `;

        document.getElementById('contenido-resumen').innerHTML = htmlResumen;
        const modal = new bootstrap.Modal(document.getElementById('modalResumen'));
        modal.show();
    }

    // ==========================================
    // FUNCIÓN: PROCESAR VENTA
    // ==========================================
    async function procesarVenta(event) {
        event.preventDefault();

        // Validaciones básicas
        if (productosVenta.length === 0) {
            mostrarAlerta('Debe agregar al menos un producto', 'danger');
            return;
        }

        if (!clienteSelect.value) {
            mostrarAlerta('Debe seleccionar un cliente', 'danger');
            return;
        }

        if (!metodoSelect.value) {
            mostrarAlerta('Debe seleccionar un método de pago', 'danger');
            return;
        }

        // Validación específica para efectivo
        if (metodoSelect.value === 'efectivo') {
            const montoRecibido = parseFloat(document.getElementById('monto_recibido').value) || 0;
            if (montoRecibido < totalVenta) {
                mostrarAlerta('El monto recibido debe ser mayor o igual al total', 'danger');
                return;
            }
        }

        // Validación para tarjeta
        if (metodoSelect.value === 'tarjeta') {
            const tipoTarjeta = document.querySelector('[name="tipo_tarjeta"]').value;
            const ultimosDigitos = document.querySelector('[name="ultimos_digitos"]').value;
            const codigoAuth = document.querySelector('[name="codigo_autorizacion"]').value;
            
            if (!tipoTarjeta || !ultimosDigitos || !codigoAuth) {
                mostrarAlerta('Complete todos los campos de pago con tarjeta', 'danger');
                return;
            }
        }

        // Validación para cuenta corriente
        if (metodoSelect.value === 'cuenta_corriente') {
            if (!clienteSeleccionado || clienteSeleccionado.limite === 0) {
                mostrarAlerta('El cliente no tiene habilitada la cuenta corriente', 'danger');
                return;
            }
            
            const nuevoSaldo = clienteSeleccionado.saldo + totalVenta;
            if (nuevoSaldo > clienteSeleccionado.limite) {
                mostrarAlerta('La venta excede el límite de crédito disponible', 'danger');
                return;
            }
        }

        // ✅ VALIDACIÓN PARA PAGO MIXTO
        if (metodoSelect.value === 'mixto') {
            const resultado = calcularPagoMixto();
            if (Math.abs(resultado.falta) > 0.01) {
                mostrarAlerta('El total de pagos mixtos debe coincidir con el total de la venta', 'danger');
                return;
            }
            
            // Validar que al menos un método esté seleccionado
            const algunMetodoSeleccionado = 
                document.getElementById('mixto-efectivo').checked ||
                document.getElementById('mixto-tarjeta').checked ||
                document.getElementById('mixto-transferencia').checked;
            
            if (!algunMetodoSeleccionado) {
                mostrarAlerta('Debe seleccionar al menos un método de pago mixto', 'danger');
                return;
            }
        }

        const btnProcesar = document.getElementById('btn-procesar');
        const textoOriginal = btnProcesar.innerHTML;
        btnProcesar.disabled = true;
        btnProcesar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando venta...';

        try {
            const datosVenta = {
                cliente_id: clienteSelect.value,
                fecha_venta: document.querySelector('[name="fecha_venta"]').value,
                lista_precios: listaPreciosSelect.value,
                metodo_pago: metodoSelect.value,
                observaciones: document.querySelector('[name="observaciones"]').value,
                productos: productosVenta.map(p => ({
                    id: p.id,
                    cantidad: p.cantidad,
                    precio: p.precio,
                    precio_costo: p.precio_costo,
                    descuento: p.descuento,
                    subtotal: p.subtotal
                })),
                subtotal: subtotalVenta,
                descuento_global: descuentoGlobal,
                total: totalVenta,
                comision: comisionActual,
                _token: document.querySelector('[name="_token"]').value
            };

            // Agregar datos específicos del método de pago
            if (metodoSelect.value === 'efectivo') {
                const montoRecibido = parseFloat(document.getElementById('monto_recibido').value);
                datosVenta.monto_recibido = montoRecibido;
                datosVenta.vuelto = montoRecibido - totalVenta;
            } else if (metodoSelect.value === 'tarjeta') {
                datosVenta.tipo_tarjeta = document.querySelector('[name="tipo_tarjeta"]').value;
                datosVenta.ultimos_digitos = document.querySelector('[name="ultimos_digitos"]').value;
                datosVenta.codigo_autorizacion = document.querySelector('[name="codigo_autorizacion"]').value;
                
                // ✅ RECALCULAR COMISIÓN Y SUMARLA AL TOTAL
                const tipoTarjeta = document.getElementById('tipo_tarjeta');
                const porcentaje = parseFloat(tipoTarjeta.options[tipoTarjeta.selectedIndex].dataset.comision || 0);
                datosVenta.comision = totalVenta * (porcentaje / 100);
                datosVenta.total = totalVenta + datosVenta.comision;
            } else if (metodoSelect.value === 'transferencia') {
                datosVenta.numero_transferencia = document.querySelector('[name="numero_transferencia"]').value;
                datosVenta.banco = document.querySelector('[name="banco"]').value;
                const fechaHora = document.querySelector('[name="fecha_transferencia"]').value;
                if (fechaHora) {
                    const [fecha, hora] = fechaHora.split('T');
                    datosVenta.fecha_transferencia = fecha;
                    datosVenta.hora_transferencia = hora;
                }
            } else if (metodoSelect.value === 'cuenta_corriente') {
                datosVenta.saldo_anterior = clienteSeleccionado.saldo;
                datosVenta.nuevo_saldo = clienteSeleccionado.saldo + totalVenta;
                datosVenta.observaciones_cc = document.querySelector('[name="observaciones_cc"]')?.value || '';
            } 
            // ✅ AGREGAR DATOS DE PAGO MIXTO
            else if (metodoSelect.value === 'mixto') {
                datosVenta.pagos_mixtos = [];
                
                if (document.getElementById('mixto-efectivo').checked) {
                    datosVenta.pagos_mixtos.push({
                        metodo: 'efectivo',
                        monto: parseFloat(document.getElementById('mixto-efectivo-monto').value) || 0
                    });
                }
                
                if (document.getElementById('mixto-tarjeta').checked) {
                    datosVenta.pagos_mixtos.push({
                        metodo: 'tarjeta',
                        monto: parseFloat(document.getElementById('mixto-tarjeta-monto').value) || 0,
                        tipo_tarjeta: document.getElementById('mixto-tipo-tarjeta').value,
                        ultimos_digitos: document.getElementById('mixto-ultimos-digitos').value,
                        codigo_autorizacion: document.getElementById('mixto-codigo-autorizacion').value
                    });
                }
                
                if (document.getElementById('mixto-transferencia').checked) {
                    const fechaHora = document.getElementById('mixto-fecha-transferencia').value;
                    const [fecha, hora] = fechaHora ? fechaHora.split('T') : ['', ''];
                    
                    datosVenta.pagos_mixtos.push({
                        metodo: 'transferencia',
                        monto: parseFloat(document.getElementById('mixto-transferencia-monto').value) || 0,
                        numero_transferencia: document.getElementById('mixto-numero-transferencia').value,
                        banco: document.getElementById('mixto-banco').value,
                        fecha_transferencia: fecha,
                        hora_transferencia: hora
                    });
                }
            }

            console.log('Enviando venta:', datosVenta);

            const response = await fetch('/api/ventas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                },
                body: JSON.stringify(datosVenta)
            });

            const resultado = await response.json();
            console.log('Respuesta servidor:', resultado);

            if (response.ok && resultado.success) {
                ventaProcesadaId = resultado.venta_id;
                
                document.getElementById('info-venta-procesada').innerHTML = `
                    <div class="row">
                        <div class="col-6"><strong>Nº Venta:</strong></div>
                        <div class="col-6 text-end">${resultado.numero_venta}</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6"><strong>Total:</strong></div>
                        <div class="col-6 text-end fs-5 text-primary">${formatearMoneda(resultado.total)}</div>
                    </div>
                    ${resultado.vuelto > 0 ? `
                    <div class="row mt-2">
                        <div class="col-6"><strong>Vuelto:</strong></div>
                        <div class="col-6 text-end fs-5 text-success">${formatearMoneda(resultado.vuelto)}</div>
                    </div>
                    ` : ''}
                `;
                
                document.getElementById('btn-imprimir-ticket').onclick = function() {
                    window.open(`/ventas/${ventaProcesadaId}/ticket`, '_blank', 'width=300,height=600');
                };
                
                document.getElementById('btn-descargar-pdf').onclick = function() {
                    window.open(`/ventas/${ventaProcesadaId}/pdf`, '_blank');
                };
                
                const modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
                modal.show();
                
                limpiarFormulario();
                
            } else {
                mostrarAlerta(resultado.message || 'Error al procesar la venta', 'danger');
            }

        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('Error de conexión. Intente nuevamente.', 'danger');
        } finally {
            btnProcesar.disabled = false;
            btnProcesar.innerHTML = textoOriginal;
        }
    }

    // ==========================================
    // FUNCIÓN: LIMPIAR FORMULARIO
    // ==========================================
    function limpiarFormulario() {
        productosVenta = [];
        totalVenta = 0;
        subtotalVenta = 0;
        descuentoGlobal = 0;
        clienteSeleccionado = null;
        comisionActual = 0;
        
        ventaForm.reset();
        descuentoGlobalInput.value = 0;
        
        document.querySelectorAll('.payment-fields').forEach(field => {
            field.style.display = 'none';
        });
        
        document.getElementById('info-cuenta-corriente').style.display = 'none';
        actualizarTablaProductos();
        document.getElementById('resultados-productos').innerHTML = '';
    }

    // ==========================================
    // INICIALIZACIÓN
    // ==========================================
    console.log('✅ Sistema de ventas mejorado cargado correctamente');
    mostrarAlerta('Sistema listo para procesar ventas. Use Ctrl+F para buscar productos y F9 para procesar.', 'info');
});
</script>
@endpush
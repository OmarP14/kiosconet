@extends('layouts.app')

@section('title', 'Reportes de Ventas')

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white py-4">
                    <h2 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Reportes de Ventas
                    </h2>
                    <p class="mb-0 mt-2">Análisis completo de tus ventas por método de pago</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar me-1"></i>Período
                            </label>
                            <select class="form-select form-select-lg" id="periodo">
                                <option value="hoy" selected>Hoy</option>
                                <option value="ayer">Ayer</option>
                                <option value="semana">Esta Semana</option>
                                <option value="mes">Este Mes</option>
                                <option value="año">Este Año</option>
                                <option value="personalizado">Personalizado</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="fecha-inicio-group" style="display: none;">
                            <label class="form-label fw-bold">Fecha Inicio</label>
                            <input type="date" class="form-control form-control-lg" id="fecha_inicio">
                        </div>
                        <div class="col-md-3" id="fecha-fin-group" style="display: none;">
                            <label class="form-label fw-bold">Fecha Fin</label>
                            <input type="date" class="form-control form-control-lg" id="fecha_fin">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-lg w-100" onclick="cargarReportes()">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen General -->
    <div class="row mb-4" id="resumen-general">
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <div class="text-muted mb-2">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Ventas</h6>
                    <h2 class="text-primary mb-0" id="cantidad-ventas">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <div class="text-muted mb-2">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                    <h6 class="text-muted mb-1">Monto Total</h6>
                    <h2 class="text-success mb-0" id="total-ventas">$0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <div class="text-muted mb-2">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <h6 class="text-muted mb-1">Promedio por Venta</h6>
                    <h2 class="text-info mb-0" id="promedio-venta">$0.00</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Métodos de Pago -->
    <div class="row mb-4" id="metodos-pago-cards">
        <div class="col-12 text-center py-5">
            <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
            <p class="mt-3 text-muted">Cargando reportes...</p>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Distribución por Método de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoMetodosPago" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
let graficoActual = null;

document.getElementById('periodo').addEventListener('change', function() {
    const fechaInicioGroup = document.getElementById('fecha-inicio-group');
    const fechaFinGroup = document.getElementById('fecha-fin-group');
    
    if (this.value === 'personalizado') {
        fechaInicioGroup.style.display = 'block';
        fechaFinGroup.style.display = 'block';
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_inicio').value = hoy;
        document.getElementById('fecha_fin').value = hoy;
    } else {
        fechaInicioGroup.style.display = 'none';
        fechaFinGroup.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    cargarReportes();
});

async function cargarReportes() {
    try {
        mostrarLoading();
        await cargarResumen();
        await cargarVentasPorMetodo();
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar los reportes: ' + error.message);
    }
}

async function cargarResumen() {
    const periodo = document.getElementById('periodo').value;
    const response = await fetch(`/reportes/resumen?periodo=${periodo}`);
    const data = await response.json();
    
    if (data.success) {
        document.getElementById('cantidad-ventas').textContent = data.cantidad_ventas;
        document.getElementById('total-ventas').textContent = data.total_ventas_formateado;
        document.getElementById('promedio-venta').textContent = data.promedio_venta_formateado;
    }
}

async function cargarVentasPorMetodo() {
    const periodo = document.getElementById('periodo').value;
    let url = `/reportes/metodo-pago?periodo=${periodo}`;
    
    if (periodo === 'personalizado') {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        url += `&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }
    
    const response = await fetch(url);
    const data = await response.json();
    
    if (data.success) {
        mostrarTarjetasMetodos(data.datos);
        mostrarGrafico(data.datos);
    }
}

function mostrarTarjetasMetodos(datos) {
    const container = document.getElementById('metodos-pago-cards');
    
    if (datos.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay ventas en el período seleccionado
                </div>
            </div>
        `;
        return;
    }
    
    let html = '';
    datos.forEach(metodo => {
        html += `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100" style="border-left: 5px solid ${metodo.color};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted mb-1">${metodo.metodo}</h6>
                                <h3 class="mb-0" style="color: ${metodo.color};">
                                    ${metodo.total_formateado}
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas ${metodo.icono} fa-2x" style="color: ${metodo.color};"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-shopping-bag me-1"></i>
                                ${metodo.cantidad} ventas
                            </small>
                            <span class="badge" style="background-color: ${metodo.color};">
                                ${metodo.porcentaje}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function mostrarGrafico(datos) {
    const ctx = document.getElementById('graficoMetodosPago').getContext('2d');
    
    if (graficoActual) {
        graficoActual.destroy();
    }
    
    if (datos.length === 0) return;
    
    const labels = datos.map(d => d.metodo);
    const values = datos.map(d => d.total);
    const colors = datos.map(d => d.color);
    
    graficoActual = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,  // ← ESTO CONTROLA LA PROPORCIÓN
            plugins: {
                legend: {
                    position: 'right',  // ← CAMBIAMOS A LA DERECHA
                    labels: {
                        padding: 15,
                        font: { size: 12 },
                        boxWidth: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const formatted = '$' + value.toLocaleString('es-AR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            return label + ': ' + formatted;
                        }
                    }
                }
            }
        }
    });
}

function mostrarLoading() {
    document.getElementById('metodos-pago-cards').innerHTML = `
        <div class="col-12 text-center py-5">
            <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
            <p class="mt-3 text-muted">Cargando reportes...</p>
        </div>
    `;
}
</script>
@endpush
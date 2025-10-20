<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KioscoNet - Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-store me-2"></i>KioscoNet
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/dashboard">Dashboard</a>
                <a class="nav-link" href="/clientes">Clientes</a>
                <a class="nav-link" href="/productos">Productos</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-shopping-cart me-2"></i>Gestión de Ventas
            </h1>
            <a href="/ventas/create" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Nueva Venta
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                    <h4>Sistema en Construcción</h4>
                    <p class="text-muted">
                        El módulo de ventas está siendo configurado.<br>
                        Pronto estará disponible con todas las funcionalidades.
                    </p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-plus-circle fa-2x text-success mb-2"></i>
                                    <h6>Nueva Venta</h6>
                                    <small class="text-muted">Registrar nueva transacción</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-list fa-2x text-info mb-2"></i>
                                    <h6>Historial</h6>
                                    <small class="text-muted">Ver ventas anteriores</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-2x text-warning mb-2"></i>
                                    <h6>Reportes</h6>
                                    <small class="text-muted">Análisis de ventas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Estado del Sistema:</strong>
            <ul class="mb-0 mt-2">
                <li>✅ Rutas configuradas</li>
                <li>✅ Controladores creados</li>
                <li>⚠️ Vistas en desarrollo</li>
                <li>⚠️ Autenticación pendiente</li>
            </ul>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KioscoNet - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <h1 class="display-4 text-primary mb-4">KioscoNet</h1>
                        <p class="lead mb-4">Sistema de Gestión Comercial</p>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="/ventas" class="btn btn-success btn-lg me-md-2">
                                Gestión de Ventas
                            </a>
                            <a href="/clientes" class="btn btn-info btn-lg me-md-2">
                                Clientes
                            </a>
                            <a href="/productos" class="btn btn-warning btn-lg">
                                Productos
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <small class="text-muted">Laravel v{{ app()->version() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
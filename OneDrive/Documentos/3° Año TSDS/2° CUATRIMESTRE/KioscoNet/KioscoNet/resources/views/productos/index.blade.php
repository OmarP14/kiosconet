@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-boxes"></i> Gestión de Productos
                </h1>
                <div>
                    <button type="button" class="btn btn-info me-2" id="btn-imprimir-etiquetas" style="display: none;">
                        <i class="fas fa-print"></i> Imprimir Etiquetas (<span id="count-selected">0</span>)
                    </button>
                    <a href="{{ route('productos.stock-bajo') }}" class="btn btn-warning me-2">
                        <i class="fas fa-exclamation-triangle"></i> Stock Bajo
                    </a>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Producto
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

    <!-- Filtros de búsqueda -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-search"></i> Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('productos.index') }}" id="filtrosForm">
                <div class="row">
                    <div class="col-md-4">
                        <label for="buscar" class="form-label">Buscar Producto</label>
                        <input type="text" 
                               class="form-control" 
                               id="buscar" 
                               name="buscar" 
                               value="{{ request('buscar') }}"
                               placeholder="Nombre, código o categoría...">
                    </div>
                    <div class="col-md-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                                {{ $categoria }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="activo" class="form-label">Estado</label>
                        <select class="form-select" id="activo" name="activo">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="stock_estado" class="form-label">Stock</label>
                        <select class="form-select" id="stock_estado" name="stock_estado">
                            <option value="">Todos</option>
                            <option value="sin_stock" {{ request('stock_estado') === 'sin_stock' ? 'selected' : '' }}>Sin Stock</option>
                            <option value="stock_bajo" {{ request('stock_estado') === 'stock_bajo' ? 'selected' : '' }}>Stock Bajo</option>
                            <option value="con_stock" {{ request('stock_estado') === 'con_stock' ? 'selected' : '' }}>Con Stock</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- Estadísticas rápidas -->
@if($productos->count() > 0)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        {{-- ✅ USANDO ESTADÍSTICAS DEL CONTROLADOR --}}
                        <h4 class="mb-0">{{ $estadisticas['total'] }}</h4>
                        <p class="mb-0">Total Productos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-boxes fa-2x"></i>
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
                        <h4 class="mb-0">{{ $estadisticas['activos'] }}</h4>
                        <p class="mb-0">Productos Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
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
                        <h4 class="mb-0">{{ $estadisticas['stock_bajo'] }}</h4>
                        <p class="mb-0">Stock Bajo</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $estadisticas['sin_stock'] }}</h4>
                        <p class="mb-0">Sin Stock</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

    <!-- Tabla de productos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Listado de Productos
            </h5>
        </div>
        <div class="card-body">
            @if($productos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="select-all">
                            </th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Proveedor</th>
                            <th class="text-center">Stock</th>
                            <th class="text-end">Precio Compra</th>
                            <th class="text-end">Precio Venta</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr class="{{ !$producto->activo ? 'table-secondary' : '' }}">
                            <td>
                                <input type="checkbox" class="form-check-input producto-checkbox"
                                       value="{{ $producto->id }}"
                                       data-nombre="{{ $producto->nombre }}"
                                       data-codigo="{{ $producto->codigo ?? '' }}"
                                       data-precio="{{ $producto->getPrecioVenta(40) }}"
                                       data-categoria="{{ $producto->categoria ?? '' }}"
                                       data-proveedor="{{ $producto->proveedor->nombre ?? '' }}"
                                       data-vencimiento="{{ $producto->fecha_vencimiento ? $producto->fecha_vencimiento->format('Y-m-d') : '' }}">
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $producto->nombre }}</strong>
                                    @if($producto->codigo)
                                        <br><small class="text-muted">{{ $producto->codigo }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($producto->categoria)
                                    <span class="badge bg-info">{{ $producto->categoria }}</span>
                                @else
                                    <span class="text-muted">Sin categoría</span>
                                @endif
                            </td>
                            <td>
                                @if($producto->proveedor)
                                    {{ $producto->proveedor->nombre }}
                                @else
                                    <span class="text-muted">Sin proveedor</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $stockClass = 'text-success';
                                    $stockIcon = 'fas fa-check-circle';
                                    if ($producto->stock <= 0) {
                                        $stockClass = 'text-danger';
                                        $stockIcon = 'fas fa-times-circle';
                                    } elseif ($producto->stock <= $producto->stock_minimo) {
                                        $stockClass = 'text-warning';
                                        $stockIcon = 'fas fa-exclamation-triangle';
                                    }
                                @endphp
                                <span class="{{ $stockClass }}">
                                    <i class="{{ $stockIcon }}"></i>
                                    {{ $producto->stock }}
                                </span>
                                @if($producto->stock_minimo > 0)
                                    <br><small class="text-muted">Min: {{ $producto->stock_minimo }}</small>
                                @endif
                            </td>
                            <td class="text-end">
                                ${{ number_format($producto->precio_compra, 2) }}
                            </td>
                            <td class="text-end">
                                {{-- ✅ CORREGIDO: Usar getPrecioVenta() correctamente --}}
                                @php
                                    $precioVenta = $producto->getPrecioVenta(40);
                                    $ganancia = $precioVenta - $producto->precio_compra;
                                    $margenPorcentaje = $producto->precio_compra > 0 
                                        ? (($precioVenta - $producto->precio_compra) / $producto->precio_compra) * 100 
                                        : 0;
                                @endphp
                                <strong>${{ number_format($precioVenta, 2) }}</strong>
                                @if($producto->precio_compra > 0)
                                    <br><small class="text-muted">{{ number_format($margenPorcentaje, 1) }}%</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($producto->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                                
                                @if($producto->fecha_vencimiento && $producto->proximoVencimiento())
                                    <br><span class="badge bg-warning">Prox. Vencimiento</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('productos.show', $producto) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('productos.edit', $producto) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            title="Ajustar stock"
                                            onclick="ajustarStock({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock }})">
                                        <i class="fas fa-warehouse"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar"
                                            onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                        Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} 
                        de {{ $productos->total() }} resultados
                    </small>
                </div>
                <div>
                    {{ $productos->appends(request()->query())->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No se encontraron productos</h5>
                <p class="text-muted">No hay productos registrados con los filtros aplicados.</p>
                <a href="{{ route('productos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Registrar Primer Producto
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para ajustar stock -->
<div class="modal fade" id="ajustarStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-warehouse"></i> Ajustar Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ajustarStockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Producto: <strong id="nombreProducto"></strong>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label for="stock_actual" class="form-label">Stock Actual</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="stock_actual" 
                                   readonly 
                                   style="background-color: #f8f9fa;">
                        </div>
                        <div class="col-md-6">
                            <label for="stock_nuevo" class="form-label">Nuevo Stock *</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="stock_nuevo" 
                                   name="stock" 
                                   min="0" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="motivo_ajuste" class="form-label">Motivo del Ajuste</label>
                        <textarea class="form-control" 
                                  id="motivo_ajuste" 
                                  name="motivo" 
                                  rows="3" 
                                  placeholder="Ej: Inventario, Merma, Corrección, etc."></textarea>
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

<!-- Modal para eliminar producto -->
<div class="modal fade" id="eliminarProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                    Eliminar Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eliminarProductoForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el producto <strong id="nombreProductoEliminar"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> 
                        Esta acción no se puede deshacer. El producto será eliminado permanentemente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ajustarStock(productoId, nombre, stockActual) {
    $('#nombreProducto').text(nombre);
    $('#stock_actual').val(stockActual);
    $('#stock_nuevo').val(stockActual);
    $('#motivo_ajuste').val('');
    $('#ajustarStockForm').attr('action', `/productos/${productoId}/ajustar-stock`);
    
    const modal = new bootstrap.Modal(document.getElementById('ajustarStockModal'));
    modal.show();
}

function eliminarProducto(productoId, nombre) {
    $('#nombreProductoEliminar').text(nombre);
    $('#eliminarProductoForm').attr('action', `/productos/${productoId}`);
    
    const modal = new bootstrap.Modal(document.getElementById('eliminarProductoModal'));
    modal.show();
}

$(document).ready(function() {
    $('#stock_nuevo').on('input', function() {
        const stockActual = parseInt($('#stock_actual').val());
        const stockNuevo = parseInt($(this).val());

        if (stockNuevo !== stockActual && !$('#motivo_ajuste').val().trim()) {
            $('#motivo_ajuste').attr('required', true);
        } else {
            $('#motivo_ajuste').removeAttr('required');
        }
    });

    // ==========================================
    // SELECCIÓN MÚLTIPLE DE PRODUCTOS
    // ==========================================
    const btnImprimirEtiquetas = document.getElementById('btn-imprimir-etiquetas');
    const countSelected = document.getElementById('count-selected');
    const selectAll = document.getElementById('select-all');
    const productosCheckboxes = document.querySelectorAll('.producto-checkbox');

    // Seleccionar/Deseleccionar todos
    selectAll.addEventListener('change', function() {
        productosCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        actualizarContadorSeleccionados();
    });

    // Actualizar contador al seleccionar individual
    productosCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', actualizarContadorSeleccionados);
    });

    function actualizarContadorSeleccionados() {
        const seleccionados = document.querySelectorAll('.producto-checkbox:checked');
        const count = seleccionados.length;

        countSelected.textContent = count;

        if (count > 0) {
            btnImprimirEtiquetas.style.display = 'inline-block';
        } else {
            btnImprimirEtiquetas.style.display = 'none';
        }

        // Actualizar estado del checkbox "Seleccionar todos"
        selectAll.checked = count === productosCheckboxes.length && count > 0;
    }

    // ==========================================
    // IMPRIMIR ETIQUETAS MASIVAMENTE
    // ==========================================
    btnImprimirEtiquetas.addEventListener('click', function() {
        const seleccionados = document.querySelectorAll('.producto-checkbox:checked');

        if (seleccionados.length === 0) {
            alert('Por favor seleccione al menos un producto');
            return;
        }

        // Recopilar datos de productos seleccionados
        const productos = [];
        seleccionados.forEach(checkbox => {
            productos.push({
                id: checkbox.value,
                nombre: checkbox.dataset.nombre,
                codigo: checkbox.dataset.codigo,
                precio: checkbox.dataset.precio,
                categoria: checkbox.dataset.categoria,
                proveedor: checkbox.dataset.proveedor,
                vencimiento: checkbox.dataset.vencimiento
            });
        });

        // Abrir ventana de impresión con etiquetas
        imprimirEtiquetasMasivas(productos);
    });

    function imprimirEtiquetasMasivas(productos) {
        // Crear ventana emergente para impresión
        const ventanaImpresion = window.open('', '_blank', 'width=800,height=600');

        let html = `
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiquetas de Productos</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></scr` + `ipt>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .etiquetas-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10mm;
            padding: 5mm;
        }

        .etiqueta {
            width: 90mm;
            height: 55mm;
            padding: 5mm;
            border: 2px solid #000;
            background-color: #fff;
            page-break-inside: avoid;
            box-sizing: border-box;
        }

        .etiqueta-header {
            text-align: center;
            background-color: #000;
            color: #fff;
            padding: 3mm;
            margin: -5mm -5mm 3mm -5mm;
        }

        .etiqueta-header h3 {
            margin: 0;
            font-size: 12pt;
        }

        .producto-nombre {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin: 2mm 0;
            min-height: 12mm;
        }

        .barcode-container {
            text-align: center;
            margin: 2mm 0;
        }

        .barcode-container svg {
            width: 100%;
            height: auto;
        }

        .codigo-texto {
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            margin: 1mm 0;
        }

        .precio-container {
            border: 3px solid #000;
            padding: 3mm;
            text-align: center;
            margin: 2mm 0;
        }

        .precio-label {
            font-size: 9pt;
            font-weight: bold;
        }

        .precio-valor {
            font-size: 20pt;
            font-weight: bold;
        }

        .info-adicional {
            font-size: 7pt;
            margin-top: 2mm;
        }

        .info-adicional p {
            margin: 1mm 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .etiqueta {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="etiquetas-container">
`;

        // Generar cada etiqueta
        productos.forEach((producto, index) => {
            html += `
        <div class="etiqueta">
            <div class="etiqueta-header">
                <h3>KIOSCONET</h3>
            </div>
            <div class="producto-nombre">${producto.nombre.toUpperCase()}</div>
            ${producto.codigo ? `
            <div class="barcode-container">
                <svg id="barcode-${index}"></svg>
            </div>
            <div class="codigo-texto">${producto.codigo}</div>
            ` : '<div class="codigo-texto" style="color: #999;">Sin código</div>'}
            <div class="precio-container">
                <div class="precio-label">PRECIO</div>
                <div class="precio-valor">$${parseFloat(producto.precio).toFixed(2)}</div>
            </div>
            <div class="info-adicional">
                ${producto.categoria ? `<p><strong>Categoría:</strong> ${producto.categoria}</p>` : ''}
                ${producto.proveedor ? `<p><strong>Proveedor:</strong> ${producto.proveedor}</p>` : ''}
                ${producto.vencimiento ? `<p><strong>Vence:</strong> ${formatearFecha(producto.vencimiento)}</p>` : ''}
            </div>
        </div>
`;
        });

        html += `
    </div>
    <scr` + `ipt>
        // Generar códigos de barras
        document.addEventListener('DOMContentLoaded', function() {
`;

        productos.forEach((producto, index) => {
            if (producto.codigo) {
                html += `
            try {
                JsBarcode("#barcode-${index}", "${producto.codigo}", {
                    format: "CODE128",
                    width: 1.5,
                    height: 40,
                    displayValue: false,
                    margin: 2,
                    background: "#ffffff",
                    lineColor: "#000000"
                });
            } catch(e) {
                console.error('Error generando código de barras ${index}:', e);
            }
`;
            }
        });

        html += `
            // Imprimir automáticamente después de cargar
            setTimeout(function() {
                window.print();
            }, 500);
        });

        function formatearFecha(fecha) {
            if (!fecha) return '';
            const partes = fecha.split('-');
            return partes[2] + '/' + partes[1] + '/' + partes[0];
        }
    </scr` + `ipt>
</body>
</html>
`;

        ventanaImpresion.document.write(html);
        ventanaImpresion.document.close();
    }
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
</style>
@endpush
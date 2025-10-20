@extends('layouts.app')

@section('title', isset($producto) ? 'Editar Producto' : 'Nuevo Producto')

@section('content')
<div class="container-fluid py-4">
    {{-- ENCABEZADO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-box {{ isset($producto) ? 'text-warning' : 'text-primary' }}"></i>
                        {{ isset($producto) ? 'Editar Producto' : 'Nuevo Producto' }}
                    </h2>
                    <p class="text-muted mb-0">
                        {{ isset($producto) ? 'Modifica la información del producto' : 'Registra un nuevo producto en el sistema' }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Listado
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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error:</strong> Por favor corrige los siguientes problemas:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- FORMULARIO --}}
    <form action="{{ isset($producto) ? route('productos.update', $producto) : route('productos.store') }}" 
          method="POST" 
          id="productoForm"
          enctype="multipart/form-data">
        @csrf
        @if(isset($producto))
            @method('PUT')
        @endif

        <div class="row">
            {{-- COLUMNA IZQUIERDA: INFORMACIÓN BÁSICA --}}
            <div class="col-md-8">
                {{-- DATOS PRINCIPALES --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información Básica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- NOMBRE --}}
                            <div class="col-md-8 mb-3">
                                <label for="nombre" class="form-label fw-bold">
                                    <i class="fas fa-tag me-1"></i>Nombre del Producto *
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $producto->nombre ?? '') }}" 
                                       placeholder="Ej: Coca Cola 500ml"
                                       required
                                       autofocus>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Nombre descriptivo del producto
                                </small>
                            </div>

                            {{-- CÓDIGO --}}
                            <div class="col-md-4 mb-3">
                                <label for="codigo" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-1"></i>Código / Barras
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control @error('codigo') is-invalid @enderror" 
                                           id="codigo" 
                                           name="codigo" 
                                           value="{{ old('codigo', $producto->codigo ?? '') }}" 
                                           placeholder="7790001234567">
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="generarCodigo"
                                            title="Generar código automático">
                                        <i class="fas fa-random"></i>
                                    </button>
                                </div>
                                @error('codigo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Código de barras o SKU único
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            {{-- CATEGORÍA --}}
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label fw-bold">
                                    <i class="fas fa-folder me-1"></i>Categoría
                                </label>
                                <input type="text" 
                                       class="form-control @error('categoria') is-invalid @enderror" 
                                       id="categoria" 
                                       name="categoria" 
                                       value="{{ old('categoria', $producto->categoria ?? '') }}" 
                                       list="categoriasList"
                                       placeholder="Ej: Bebidas">
                                <datalist id="categoriasList">
                                    <option value="Bebidas">
                                    <option value="Snacks">
                                    <option value="Golosinas">
                                    <option value="Lácteos">
                                    <option value="Panadería">
                                    <option value="Cigarrillos">
                                    <option value="Limpieza">
                                    <option value="Otros">
                                </datalist>
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Categoría para organizar productos
                                </small>
                            </div>

                            {{-- PROVEEDOR --}}
                            <div class="col-md-6 mb-3">
                                <label for="proveedor_id" class="form-label fw-bold">
                                    <i class="fas fa-truck me-1"></i>Proveedor *
                                </label>
                                <select class="form-select @error('proveedor_id') is-invalid @enderror" 
                                        id="proveedor_id" 
                                        name="proveedor_id" 
                                        required>
                                    <option value="">Seleccionar proveedor...</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" 
                                                {{ old('proveedor_id', $producto->proveedor_id ?? '') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('proveedor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- FECHA DE VENCIMIENTO --}}
                            <div class="col-md-6 mb-3">
                                <label for="fecha_vencimiento" class="form-label fw-bold">
                                    <i class="fas fa-calendar-times me-1"></i>Fecha de Vencimiento
                                </label>
                                <input type="date" 
                                       class="form-control @error('fecha_vencimiento') is-invalid @enderror" 
                                       id="fecha_vencimiento" 
                                       name="fecha_vencimiento" 
                                       value="{{ old('fecha_vencimiento', isset($producto->fecha_vencimiento) ? $producto->fecha_vencimiento->format('Y-m-d') : '') }}"
                                       min="{{ date('Y-m-d') }}">
                                @error('fecha_vencimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Fecha de vencimiento del producto (opcional)
                                </small>
                            </div>

                            {{-- ESTADO --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-toggle-on me-1"></i>Estado
                                </label>
                                <div class="form-check form-switch" style="padding-left: 2.5rem;">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="activo" 
                                           name="activo" 
                                           value="1"
                                           style="width: 3rem; height: 1.5rem;"
                                           {{ old('activo', $producto->activo ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        <span id="estadoTexto" class="badge bg-success">Activo</span>
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Productos inactivos no aparecen en ventas
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PRECIOS Y STOCK --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-dollar-sign me-2"></i>Precios y Stock
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- PRECIO DE COMPRA --}}
                            <div class="col-md-4 mb-3">
                                <label for="precio_compra" class="form-label fw-bold">
                                    <i class="fas fa-shopping-cart me-1"></i>Precio de Compra *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                        class="form-control @error('precio_compra') is-invalid @enderror" 
                                        id="precio_compra" 
                                        name="precio_compra" 
                                        value="{{ old('precio_compra', $producto->precio_compra ?? '') }}" 
                                        step="0.01" 
                                        min="0"
                                        placeholder="0.00"
                                        required>
                                </div>
                                @error('precio_compra')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Precio al que compras el producto
                                </small>
                            </div>

                            {{-- PRECIO SUGERIDO DE VENTA --}}
                            <div class="col-md-4 mb-3">
                                <label for="precio_venta_sugerido" class="form-label fw-bold">
                                    <i class="fas fa-tag me-1"></i>Precio Venta Sugerido
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                        class="form-control bg-light" 
                                        id="precio_venta_sugerido" 
                                        step="0.01" 
                                        readonly
                                        placeholder="0.00">
                                    <span class="input-group-text bg-success text-white" id="margen_ganancia">
                                        0%
                                    </span>
                                </div>
                                <small class="form-text text-muted">
                                Calculado automáticamente (<strong id="texto_margen_ganancia" style="color: #28a745;">0%</strong> ganancia)
                                    <i class="fas fa-info-circle"></i> 
                                    Ejemplos: <strong>10%</strong> = ${{ number_format(1620 * 1.10, 0) }}, 
                                    <strong>20%</strong> = ${{ number_format(1620 * 1.20, 0) }}, 
                                    <strong>40%</strong> = ${{ number_format(1620 * 1.40, 0) }}
                                </small>
                            </div>

                            {{-- MARGEN PERSONALIZADO --}}
                                <div class="col-md-4 mb-3">
                                    <label for="margen_custom" class="form-label fw-bold">
                                        <i class="fas fa-percent me-1"></i>Margen de Ganancia
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                            class="form-control" 
                                            id="margen_custom" 
                                            value="0"
                                            min="0" 
                                            max="500"
                                            step="0.1"
                                            placeholder="Ej: 10, 15, 40">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="form-text text-muted">
                                        Escribe el porcentaje de ganancia deseado (Ej: 10% = +$162)
                                    </small>
                                </div>
                        </div>
                        <div class="row">
                            {{-- STOCK ACTUAL --}}
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label fw-bold">
                                    <i class="fas fa-boxes me-1"></i>Stock Actual *
                                </label>
                                <input type="number" 
                                       class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock', $producto->stock ?? 0) }}" 
                                       min="0"
                                       required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Cantidad disponible en inventario
                                </small>
                            </div>

                            {{-- STOCK MÍNIMO --}}
                            <div class="col-md-6 mb-3">
                                <label for="stock_minimo" class="form-label fw-bold">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Stock Mínimo *
                                </label>
                                <input type="number" 
                                       class="form-control @error('stock_minimo') is-invalid @enderror" 
                                       id="stock_minimo" 
                                       name="stock_minimo" 
                                       value="{{ old('stock_minimo', $producto->stock_minimo ?? 5) }}" 
                                       min="0"
                                       required>
                                @error('stock_minimo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Alerta cuando el stock esté bajo este nivel
                                </small>
                            </div>
                        </div>

                        {{-- ALERTA DE STOCK --}}
                        <div id="alerta_stock" class="alert alert-warning d-none">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>¡Atención!</strong> El stock actual está por debajo del mínimo recomendado.
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: INFORMACIÓN ADICIONAL --}}
            <div class="col-md-4">
                {{-- IMAGEN DEL PRODUCTO --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-image me-2"></i>Imagen del Producto
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div id="preview_imagen" class="border rounded p-3" style="min-height: 200px;">
                                @if(isset($producto) && $producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         class="img-fluid rounded" 
                                         alt="{{ $producto->nombre }}"
                                         style="max-height: 200px;">
                                @else
                                    <i class="fas fa-image fa-5x text-muted"></i>
                                    <p class="text-muted mt-2">Sin imagen</p>
                                @endif
                            </div>
                        </div>
                        <input type="file" 
                               class="form-control @error('imagen') is-invalid @enderror" 
                               id="imagen" 
                               name="imagen" 
                               accept="image/*"
                               onchange="previewImage(event)">
                        @error('imagen')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Formatos: JPG, PNG, GIF (Max: 2MB)
                        </small>
                    </div>
                </div>

                {{-- RESUMEN --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>Resumen
                        </h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-7">Precio Compra:</dt>
                            <dd class="col-5 text-end" id="resumen_compra">$0.00</dd>

                            <dt class="col-7">Precio Venta:</dt>
                            <dd class="col-5 text-end text-success fw-bold" id="resumen_venta">$0.00</dd>

                            <dt class="col-7">Ganancia Unitaria:</dt>
                            <dd class="col-5 text-end text-primary fw-bold" id="resumen_ganancia">$0.00</dd>

                            <dt class="col-7">Stock:</dt>
                            <dd class="col-5 text-end" id="resumen_stock">0 unidades</dd>

                            <dt class="col-7">Valor Inventario:</dt>
                            <dd class="col-5 text-end fw-bold" id="resumen_inventario">$0.00</dd>
                        </dl>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                {{ isset($producto) ? 'Guardar Cambios' : 'Registrar Producto' }}
                            </button>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>

                        @if(isset($producto))
                        <hr>
                        <div class="text-muted small">
                            <i class="fas fa-clock me-1"></i>
                            Creado: {{ $producto->created_at->format('d/m/Y H:i') }}
                            <br>
                            <i class="fas fa-edit me-1"></i>
                            Modificado: {{ $producto->updated_at->format('d/m/Y H:i') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // CALCULAR PRECIO DE VENTA AUTOMÁTICAMENTE
        // ==========================================
       function calcularPrecioVenta() {
            const precioCompra = parseFloat(document.getElementById('precio_compra').value) || 0;
            const margen = parseFloat(document.getElementById('margen_custom').value) || 0; // ← AHORA ES 0
            
            const precioVenta = precioCompra * (1 + margen / 100);
            const ganancia = precioVenta - precioCompra;
            const stock = parseInt(document.getElementById('stock').value) || 0;
            const valorInventario = precioCompra * stock;
            
            // Actualizar campos
            document.getElementById('precio_venta_sugerido').value = precioVenta.toFixed(2);
            document.getElementById('margen_ganancia').textContent = margen.toFixed(1) + '%';
            
            // Actualizar texto dinámico del margen
            document.getElementById('texto_margen_ganancia').textContent = margen.toFixed(1) + '%';
            
            // Actualizar resumen
            document.getElementById('resumen_compra').textContent = '$' + precioCompra.toFixed(2);
            document.getElementById('resumen_venta').textContent = '$' + precioVenta.toFixed(2);
            document.getElementById('resumen_ganancia').textContent = '$' + ganancia.toFixed(2);
            document.getElementById('resumen_inventario').textContent = '$' + valorInventario.toFixed(2);

                // ✅ NUEVO: Mostrar ganancia por unidad en el placeholder
            if (precioCompra > 0 && margen > 0) {
                const gananciaUnidad = precioCompra * (margen / 100);
                document.getElementById('margen_custom').setAttribute(
                    'placeholder', 
                    `Ganancia: +$${gananciaUnidad.toFixed(2)}`
                );
            }
        }
        
        // Event listeners
        document.getElementById('precio_compra').addEventListener('input', calcularPrecioVenta);
        document.getElementById('margen_custom').addEventListener('input', calcularPrecioVenta);
        document.getElementById('stock').addEventListener('input', actualizarStock);
        document.getElementById('stock_minimo').addEventListener('input', actualizarStock);
        
        // Calcular al cargar
        calcularPrecioVenta();
        actualizarStock();
    });

// ==========================================
// PREVISUALIZAR IMAGEN
// ==========================================
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview_imagen');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     class="img-fluid rounded" 
                     style="max-height: 200px;" 
                     alt="Vista previa">
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@push('styles')
<style>
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.card {
    border-radius: 15px;
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5em 0.8em;
}

#preview_imagen {
    transition: all 0.3s ease;
}

#preview_imagen:hover {
    transform: scale(1.02);
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

/* ✅ NUEVO: Estilo para el margen dinámico */
#texto_margen_ganancia {
    color: #28a745;
    font-weight: 700;
    font-size: 1.1em;
}

@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
}
</style>
@endpush
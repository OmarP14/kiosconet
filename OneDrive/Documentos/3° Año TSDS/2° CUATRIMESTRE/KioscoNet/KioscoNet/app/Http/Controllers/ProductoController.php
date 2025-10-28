<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;

/**
 * =====================================================
 * CONTROLADOR DE PRODUCTOS - KIOSCONET
 * =====================================================
 * Maneja todas las operaciones relacionadas con productos
 * ✅ VERSIÓN CORREGIDA CON TODOS LOS MÉTODOS
 * =====================================================
 */
class ProductoController extends Controller
{
    // ==========================================
    // BÚSQUEDA API (para módulo de ventas)
    // ==========================================
    
    /**
     * Buscar productos para ventas (API)
     */
    public function buscarApi(Request $request)
    {
        try {
            $termino = trim($request->get('termino', ''));
            
            if (empty($termino)) {
                $termino = trim($request->get('q', ''));
            }
            
            if (strlen($termino) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingresa al menos 2 caracteres para buscar',
                    'data' => []
                ], 200);
            }

            $productos = Producto::where('activo', true)
                ->where(function ($query) use ($termino) {
                    $query->where('nombre', 'LIKE', "%{$termino}%")
                          ->orWhere('codigo', 'LIKE', "%{$termino}%");
                })
                ->with('proveedor:id,nombre')
                ->select('id', 'nombre', 'codigo', 'stock', 'precio_compra', 'stock_minimo', 'proveedor_id', 'fecha_vencimiento')
                ->orderBy('nombre', 'asc')
                ->limit(20)
                ->get();

            $resultados = $productos->map(function ($producto) {
                $margenGanancia = 1.40; // 40% de ganancia
                $precioVenta = $producto->precio_compra * $margenGanancia;
                
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'codigo' => $producto->codigo ?? 'S/C',
                    'stock' => (int) $producto->stock,
                    'precio' => round($precioVenta, 2),
                    'precio_costo' => round($producto->precio_compra, 2),
                    'precio_formateado' => '$' . number_format($precioVenta, 2),
                    'stock_bajo' => $producto->stock <= ($producto->stock_minimo ?? 5),
                    'proveedor' => optional($producto->proveedor)->nombre ?? 'Sin proveedor',
                    'disponible' => $producto->stock > 0,
                    'activo' => $producto->activo,
                    'dias_hasta_vencimiento' => $producto->diasHastaVencimiento(),
                ];
            });

            return response()->json([
                'success' => true,
                'count' => $resultados->count(),
                'message' => $resultados->count() > 0 
                    ? "Se encontraron {$resultados->count()} productos" 
                    : 'No se encontraron productos',
                'data' => $resultados
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de productos API', [
                'error' => $e->getMessage(),
                'termino' => $request->get('termino'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al buscar productos',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
                'data' => []
            ], 500);
        }
    }

    // ==========================================
    // LISTADO DE PRODUCTOS
    // ==========================================
    
    /**
     * ✅ CORREGIDO: Ahora pasa la variable $categorias
     */
    public function index(Request $request)
{
    $query = Producto::with('proveedor');

    // Filtro de búsqueda
    if ($request->filled('buscar')) {
        $query->buscar($request->buscar);
    }

    // Filtro de categoría
    if ($request->filled('categoria')) {
        $query->categoria($request->categoria);
    }

    // Filtro de estado
    if ($request->filled('activo')) {
        $query->estado($request->activo);
    }

    // Filtro de stock
    if ($request->filled('stock_estado')) {
        switch ($request->stock_estado) {
            case 'sin_stock':
                $query->sinStock();
                break;
            case 'stock_bajo':
                $query->stockBajo();
                break;
            case 'con_stock':
                $query->where('stock', '>', 0);
                break;
        }
    }

    $productos = $query->orderBy('nombre', 'asc')->paginate(15);

    // ✅ OBTENER CATEGORÍAS ÚNICAS
    $categorias = Producto::whereNotNull('categoria')
        ->distinct()
        ->pluck('categoria')
        ->sort();

    // ✅ NUEVO: Calcular estadísticas
    $estadisticas = [
        'total' => Producto::count(),
        'activos' => Producto::query()->activos()->count(),
        'stock_bajo' => Producto::query()->stockBajo()->count(),
        'sin_stock' => Producto::query()->sinStock()->count(),
    ];

    return view('productos.index', compact('productos', 'categorias', 'estadisticas'));
}

    /**
     * ✅ NUEVO: Listar productos con stock bajo
     */
    public function stockBajo()
    {
        $productos = Producto::query()
            ->stockBajo()
            ->with('proveedor')
            ->orderBy('stock', 'asc')
            ->paginate(15);

        $categorias = Producto::whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria')
            ->sort();

        return view('productos.stock-bajo', compact('productos', 'categorias'));
    }

    // ==========================================
    // CREAR PRODUCTO
    // ==========================================
    
    public function create()
    {
        // ✅ SIN FILTRO POR 'activo' porque la columna no existe
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();

        return view('productos.create', compact('proveedores'));
    }

    /**
     * ✅ MEJORADO: Usa StoreProductoRequest para validación
     */
    public function store(StoreProductoRequest $request)
    {
        // ✅ La validación ya se realizó en StoreProductoRequest

        try {
            $datos = $request->except('imagen');
            $datos['activo'] = $request->has('activo') ? 1 : 0;

            // Manejar imagen
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $ruta = $imagen->storeAs('productos', $nombreImagen, 'public');
                $datos['imagen'] = $ruta;
            }

            $producto = Producto::create($datos);

            Log::info('Producto creado', [
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('productos.index')
                ->with('success', '¡Producto creado exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error al crear producto', [
                'error' => $e->getMessage(),
                'datos' => $request->except('imagen')
            ]);

            return redirect()->back()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ==========================================
    // MOSTRAR PRODUCTO
    // ==========================================
    
    public function show($id)
    {
        $producto = Producto::with(['proveedor', 'detallesVenta.venta'])
            ->findOrFail($id);

        return view('productos.show', compact('producto'));
    }

    // ==========================================
    // EDITAR PRODUCTO
    // ==========================================
    
   public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        // ✅ SIN FILTRO POR 'activo'
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();

        return view('productos.edit', compact('producto', 'proveedores'));
    }

    /**
     * ✅ MEJORADO: Usa UpdateProductoRequest para validación
     */
    public function update(UpdateProductoRequest $request, $id)
    {
        // ✅ La validación ya se realizó en UpdateProductoRequest
        $producto = Producto::findOrFail($id);

        try {
            $datos = $request->except('imagen');
            $datos['activo'] = $request->has('activo') ? 1 : 0;

            // Manejar nueva imagen
            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior
                if ($producto->imagen) {
                    Storage::disk('public')->delete($producto->imagen);
                }

                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $ruta = $imagen->storeAs('productos', $nombreImagen, 'public');
                $datos['imagen'] = $ruta;
            }

            $producto->update($datos);

            Log::info('Producto actualizado', [
                'producto_id' => $producto->id,
                'cambios' => $datos,
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('productos.index')
                ->with('success', '¡Producto actualizado exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error al actualizar producto', [
                'producto_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ==========================================
    // ELIMINAR PRODUCTO
    // ==========================================
    
    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            
            // Verificar si tiene ventas
            if ($producto->detallesVenta()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el producto porque tiene ventas registradas. Puede desactivarlo en su lugar.');
            }

            $nombreProducto = $producto->nombre;
            
            // Eliminar imagen si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $producto->delete();

            Log::info('Producto eliminado', [
                'producto_id' => $id,
                'nombre' => $nombreProducto,
                'usuario_id' => auth()->id()
            ]);

            return redirect()->route('productos.index')
                ->with('success', "Producto '{$nombreProducto}' eliminado exitosamente");

        } catch (\Exception $e) {
            Log::error('Error al eliminar producto', [
                'producto_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    // ==========================================
    // AJUSTAR STOCK
    // ==========================================
    
    /**
     * ✅ NUEVO: Ajustar stock de un producto
     */
    public function ajustarStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stock' => 'required|integer|min:0',
            'motivo' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Datos inválidos para ajustar stock');
        }

        // ✅ MEJORADO: Agregada transacción DB para mayor seguridad
        DB::beginTransaction();

        try {
            $producto = Producto::findOrFail($id);
            $stockAnterior = $producto->stock;
            $stockNuevo = $request->stock;
            $diferencia = $stockNuevo - $stockAnterior;

            $producto->update(['stock' => $stockNuevo]);

            Log::info('Stock ajustado', [
                'producto_id' => $producto->id,
                'producto' => $producto->nombre,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'diferencia' => $diferencia,
                'motivo' => $request->motivo,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', "Stock actualizado correctamente. {$producto->nombre}: {$stockAnterior} → {$stockNuevo}");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al ajustar stock', [
                'producto_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Error al ajustar stock: ' . $e->getMessage());
        }
    }

    /**
     * ✅ NUEVO: Cambiar estado activo/inactivo
     */
    public function toggleEstado($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->activo = !$producto->activo;
            $producto->save();

            $estado = $producto->activo ? 'activado' : 'desactivado';

            Log::info('Estado de producto cambiado', [
                'producto_id' => $producto->id,
                'nuevo_estado' => $producto->activo,
                'usuario_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('success', "Producto {$estado} exitosamente");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cambiar estado del producto');
        }
    }

    // ==========================================
    // VERIFICAR STOCK (API)
    // ==========================================
    
    public function verificarStock(Request $request)
    {
        $productoId = $request->input('producto_id');
        $cantidad = (int) $request->input('cantidad', 1);

        $producto = Producto::find($productoId);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'disponible' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $disponible = $producto->stock >= $cantidad;

        return response()->json([
            'success' => true,
            'disponible' => $disponible,
            'stock_actual' => $producto->stock,
            'stock_solicitado' => $cantidad,
            'stock_restante' => max(0, $producto->stock - $cantidad),
            'message' => $disponible 
                ? 'Stock disponible' 
                : "Stock insuficiente. Solo hay {$producto->stock} unidades disponibles"
        ]);
    }

    /**
     * ✅ NUEVO: Obtener producto por ID (API)
     */
    public function obtenerProducto($id)
    {
        try {
            $producto = Producto::with('proveedor')
                ->where('activo', true)
                ->findOrFail($id);

            $margen = 1.40;
            $precioVenta = $producto->precio_compra * $margen;

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'codigo' => $producto->codigo,
                    'stock' => $producto->stock,
                    'precio' => round($precioVenta, 2),
                    'precio_formateado' => '$' . number_format($precioVenta, 2),
                    'disponible' => $producto->stock > 0,
                    'proveedor' => optional($producto->proveedor)->nombre
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }
    }
}
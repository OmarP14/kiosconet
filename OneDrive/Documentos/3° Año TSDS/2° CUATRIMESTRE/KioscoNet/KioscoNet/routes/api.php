<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Cliente;
use App\Models\Producto;
use Carbon\Carbon;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Clientes
Route::get('/clientes/{cliente}/limite-credito', function($clienteId) {
    try {
        $cliente = Cliente::findOrFail($clienteId);
        return response()->json([
            'success' => true,
            'limite_credito' => $cliente->limite_credito ?? 1000.00,
            'saldo_actual' => $cliente->saldo_cuenta_corriente ?? 0.00,
            'disponible' => ($cliente->limite_credito ?? 1000.00) - ($cliente->saldo_cuenta_corriente ?? 0.00),
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false], 404);
    }
});

// Productos - Validar stock
Route::post('/productos/validar-stock', function(Request $request) {
    $productos = $request->get('productos', []);
    $errores = [];

    foreach ($productos as $item) {
        $producto = Producto::find($item['id']);
        if (!$producto || $producto->stock < $item['cantidad']) {
            $errores[] = "Stock insuficiente";
        }
    }

    return response()->json([
        'valido' => count($errores) === 0,
        'errores' => $errores
    ]);
});
// BUSCAR PRODUCTOS (con alertas de stock y vencimiento)
Route::get('/productos/buscar-con-alertas', function(Request $request) {
    $query = Producto::where('activo', true);
    
    if ($request->has('termino')) {
        $termino = $request->get('termino');
        $query->where(function($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('codigo_barra', 'like', "%{$termino}%");
        });
    }
    
    $productos = $query->limit(20)->get()->map(function($producto) {
        // Calcular días hasta vencimiento
        $diasVencimiento = null;
        if ($producto->fecha_vencimiento) {
            $diasVencimiento = Carbon::now()->diffInDays(
                Carbon::parse($producto->fecha_vencimiento), 
                false
            );
        }
        
        return [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'stock' => $producto->stock,
            'stock_minimo' => $producto->stock_minimo ?? 10,
            'precio' => $producto->precio,
            'dias_hasta_vencimiento' => $diasVencimiento,
            'stock_bajo' => $producto->stock <= ($producto->stock_minimo ?? 10),
            'proximo_vencer' => $diasVencimiento && $diasVencimiento <= 30,
        ];
    });
    
    return response()->json([
        'success' => true,
        'data' => $productos
    ]);
});
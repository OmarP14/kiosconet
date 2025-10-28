<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// AUTENTICACIÓN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTA PRINCIPAL
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// RUTAS PROTEGIDAS
Route::middleware(['auth'])->group(function () {

    // VENTAS
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->name('index');
        Route::get('/create', [VentaController::class, 'create'])->name('create');
        Route::get('/{venta}', [VentaController::class, 'show'])->name('show');
        Route::delete('/{venta}', [VentaController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/anular', [VentaController::class, 'anular'])->name('anular');
        // AGREGA ESTAS NUEVAS:
        Route::get('/{venta}/ticket', [VentaController::class, 'imprimirTicket'])->name('ticket');
        Route::get('/{venta}/pdf', [VentaController::class, 'descargarPDF'])->name('pdf');
        Route::get('/{venta}/comprobante', [VentaController::class, 'comprobante'])->name('comprobante');
        Route::get('/{venta}/reimprimir', [VentaController::class, 'reimprimir'])->name('reimprimir');
    });

    // ==========================================
    // API INTERNA (AJAX) - CONSOLIDADO
    // ==========================================
    Route::prefix('api')->name('api.')->group(function () {
        // Productos
        Route::get('/productos/buscar', [ProductoController::class, 'buscarApi'])->name('productos.buscar');
        Route::get('/productos/{id}', [ProductoController::class, 'obtenerProducto'])->name('productos.obtener');
        Route::post('/productos/verificar-stock', [ProductoController::class, 'verificarStock'])->name('productos.verificar-stock');

        // Ventas
        Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
        Route::get('/ventas/{venta}/detalles', [VentaController::class, 'obtenerDetalles'])->name('ventas.detalles');
    });
    // ==========================================
    // CLIENTES (Rutas completas)
    // ==========================================
    Route::middleware(['auth'])->prefix('clientes')->name('clientes.')->group(function () {
        // Listado
        Route::get('/', [ClienteController::class, 'index'])->name('index');
        
        // Crear
        Route::get('/crear', [ClienteController::class, 'create'])->name('create');
        Route::post('/', [ClienteController::class, 'store'])->name('store');
        
        // ✅ Cuentas corrientes (debe ir ANTES de /{cliente})
        Route::get('/cuentas-corrientes', [ClienteController::class, 'cuentasCorrientes'])->name('cuentas-corrientes');
        
        // Ver detalle
        Route::get('/{cliente}', [ClienteController::class, 'show'])->name('show');
        
        // Editar
        Route::get('/{cliente}/editar', [ClienteController::class, 'edit'])->name('edit');
        Route::put('/{cliente}', [ClienteController::class, 'update'])->name('update');
        
        // Eliminar
        Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('destroy');
        
        // Ajustar saldo
        Route::patch('/{cliente}/ajustar-saldo', [ClienteController::class, 'ajustarSaldo'])->name('ajustar-saldo');
        
        // Estado de cuenta
        Route::get('/{cliente}/estado-cuenta', [ClienteController::class, 'estadoCuenta'])->name('estado-cuenta');
    });

    // ==========================================
    // PRODUCTOS
    // ==========================================
    Route::middleware(['auth'])->prefix('productos')->name('productos.')->group(function () {
        // Rutas básicas CRUD
        Route::get('/', [ProductoController::class, 'index'])->name('index');
        Route::get('/crear', [ProductoController::class, 'create'])->name('create');
        Route::post('/', [ProductoController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductoController::class, 'show'])->name('show');
        Route::get('/{id}/editar', [ProductoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductoController::class, 'destroy'])->name('destroy');

        // Rutas especiales
        Route::get('/listado/stock-bajo', [ProductoController::class, 'stockBajo'])->name('stock-bajo');
        Route::patch('/{id}/ajustar-stock', [ProductoController::class, 'ajustarStock'])->name('ajustar-stock');
        Route::patch('/{id}/toggle-estado', [ProductoController::class, 'toggleEstado'])->name('toggle-estado');
    });

    // ==========================================
    // CAJA (Sistema Simple)
    // ==========================================
    Route::middleware(['auth'])->prefix('caja')->name('caja.')->group(function () {
        // Vista principal
        Route::get('/', [CajaController::class, 'index'])->name('index');
        
        // Crear movimiento
        Route::get('/crear', [CajaController::class, 'create'])->name('create');
        Route::post('/', [CajaController::class, 'store'])->name('store');
        
        // Ver detalle de movimiento
        Route::get('/{id}', [CajaController::class, 'show'])->name('show');
        
        // Editar movimiento
        Route::get('/{id}/editar', [CajaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CajaController::class, 'update'])->name('update');
        
        // Eliminar movimiento
        Route::delete('/{id}', [CajaController::class, 'destroy'])->name('destroy');
        
        // Vistas adicionales
        Route::get('/listado/movimientos', [CajaController::class, 'index'])->name('movimientos');
        Route::get('/listado/arqueo', [CajaController::class, 'arqueo'])->name('arqueo');
        Route::post('/cerrar-caja', [CajaController::class, 'cerrarCaja'])->name('cerrarCaja');
    });

    // PERFIL
    Route::prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/', [PerfilController::class, 'index'])->name('index');
        Route::get('/edit', [PerfilController::class, 'edit'])->name('edit');
        Route::put('/update', [PerfilController::class, 'update'])->name('update');
        Route::put('/cambiar-password', [PerfilController::class, 'cambiarPassword'])->name('cambiarPassword');
    });

    // USUARIOS
    // ==========================================
    Route::middleware(['auth'])->prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/crear', [UsuarioController::class, 'create'])->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::get('/{usuario}', [UsuarioController::class, 'show'])->name('show');
        Route::get('/{usuario}/editar', [UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
        Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy');
    });

    // PROVEEDORES
    // ==========================================
    Route::middleware(['auth'])->prefix('proveedores')->name('proveedores.')->group(function () {
        // Listado
        Route::get('/', [ProveedorController::class, 'index'])->name('index');
        
        // Crear
        Route::get('/crear', [ProveedorController::class, 'create'])->name('create');
        Route::post('/', [ProveedorController::class, 'store'])->name('store');
        
        // Ver detalle
        Route::get('/{proveedor}', [ProveedorController::class, 'show'])->name('show');
        
        // Editar
        Route::get('/{proveedor}/editar', [ProveedorController::class, 'edit'])->name('edit');
        Route::put('/{proveedor}', [ProveedorController::class, 'update'])->name('update');
        
        // Eliminar
        Route::delete('/{proveedor}', [ProveedorController::class, 'destroy'])->name('destroy');
        
        // Productos del proveedor
        Route::get('/{proveedor}/productos', [ProveedorController::class, 'productos'])->name('productos');
    });

    // REPORTES
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('/ventas', [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('/productos', [ReporteController::class, 'productos'])->name('productos');
        Route::get('/clientes', [ReporteController::class, 'clientes'])->name('clientes');
        Route::get('metodo-pago', [ReporteController::class, 'ventasPorMetodoPago'])->name('metodo-pago');
        Route::get('resumen', [ReporteController::class, 'resumenVentas'])->name('resumen');
    });

    // ==========================================
    // RUTA PÚBLICA (sin autenticación)
    // ==========================================
    // Ruta pública para ver comprobantes (sin necesidad de login)
    Route::get('/comprobante/{token}', [VentaController::class, 'consultarComprobante'])->name('comprobante.publico');
});
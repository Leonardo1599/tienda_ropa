<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\PasarelaController;

if (!function_exists('auth')) {
    function auth()
    {
        return app('auth');
    }
}

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard.index')
        : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProductoController::class, 'index'])->name('dashboard.index');

    // Productos
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::delete('/productos/{producto}/soft', [ProductoController::class, 'softDelete'])->name('productos.softDelete');
    Route::post('/productos/{producto}/restore', [ProductoController::class, 'restore'])->name('productos.restore');

    // Carrito
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito', [CarritoController::class, 'store'])->name('carrito.store');
    Route::put('/carrito/{carrito}', [CarritoController::class, 'update'])->name('carrito.update');
    Route::delete('/carrito/{carrito}', [CarritoController::class, 'destroy'])->name('carrito.destroy');

    // Ordenes
    Route::get('/ordenes', [OrdenController::class, 'index'])->name('ordenes.index');
    Route::post('/ordenes', [OrdenController::class, 'store'])->name('ordenes.store');
    Route::get('/ordenes/comprobante/{orden}', [OrdenController::class, 'descargarComprobante'])->name('ordenes.comprobante');
    Route::delete('/ordenes/{orden}/soft', [OrdenController::class, 'softDelete'])->name('ordenes.softDelete');
    Route::post('/ordenes/{orden}/restore', [OrdenController::class, 'restore'])->name('ordenes.restore');

    // Rutas solo para admin (protección manual en el controlador)
    Route::get('/admin/ordenes', [OrdenController::class, 'adminIndex'])->name('admin.ordenes');
    Route::get('/admin/pasarelas', [PasarelaController::class, 'edit'])->name('admin.pasarelas');
    Route::post('/admin/pasarelas', [PasarelaController::class, 'update'])->name('admin.pasarelas.update');
    Route::put('/admin/ordenes/{orden}/estado', [OrdenController::class, 'actualizarEstado'])->name('admin.ordenes.estado');
    Route::put('/admin/ordenes/{orden}/comprobante', [OrdenController::class, 'validarComprobante'])->name('admin.ordenes.comprobante');
});

// API para pagos
Route::post('/api/paypal/confirm', [OrdenController::class, 'confirmarPaypal'])->name('api.paypal.confirm');
Route::post('/api/izipay/confirm', [OrdenController::class, 'confirmarIzipay'])->name('api.izipay.confirm');

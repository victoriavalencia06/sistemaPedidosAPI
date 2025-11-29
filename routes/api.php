<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\DetallePedidoController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Información del usuario autenticado
    Route::get('/me', [AuthController::class, 'me']);

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('rol', RolController::class);
    Route::apiResource('usuario', UsuarioController::class);
    Route::apiResource('categoria', CategoriaController::class);
    Route::apiResource('producto', ProductoController::class);
    Route::apiResource('pedido', PedidoController::class);
    Route::apiResource('detalle-pedido', DetallePedidoController::class);
    Route::apiResource('reporte', ReporteController::class);
});

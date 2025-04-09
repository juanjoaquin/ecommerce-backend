<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('pedidos/hacer-pedido', [PedidoController::class, 'store']);


    Route::get('admin/pedidos', [PedidoController::class, 'index']);
    Route::put('admin/pedidos/{id}', [PedidoController::class, 'update']);

    Route::put('admin/productos/{id}', [ProductoController::class, 'update']);

    
    Route::get('categorias', [CategoriaController::class, 'index']);
    Route::get('productos', [ProductoController::class, 'index']);
});


Route::post('registro', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\Productos\ProductoController;
use App\Http\Controllers\Api\V1\Cliente\ClienteController;
use App\Http\Controllers\Api\V1\Blog\BlogController;

Route::prefix('v1')->group(function () {

    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware(['auth:sanctum', 'role:ADMIN|USER']);
    });

    Route::controller(UserController::class)->prefix('users')->group(function(){
        Route::middleware(['auth:sanctum', 'role:ADMIN'])->group(function () {
            Route::post('/', 'store');
            Route::get('/', 'index');
            Route::delete('/{id}', 'destroy');
            Route::put('/{id}', 'update');
        });
    });

    Route::controller(ProductoController::class)->prefix('productos')->group(function(){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::middleware(['auth:sanctum', 'role:ADMIN|USER', 'permission:ENVIAR'])->group(function () {
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    Route::controller(BlogController::class)->prefix('blogs')->group(function(){
        Route::get('/', 'index');
        Route::get('/{blog}', 'show');

        Route::middleware(['auth:sanctum', 'role:ADMIN|USER'])->group(function () {
            Route::post('/', 'store');
            Route::put('/{blog}', 'update');
            Route::delete('/{blog}', 'destroy');
        });
    });

    Route::controller(ClienteController::class)->prefix('clientes')->group(function(){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::middleware(['auth:sanctum', 'role:ADMIN|USER'])->group(function () {
            Route::post('/', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });

    // Route::controller(BloqueContenidoController::class)->prefix('bloques')->group(function(){
    //     Route::get('/', 'index');
    //     Route::get('/{bloque}', 'show');

    //     Route::middleware(['auth:sanctum', 'role:ADMIN|USER'])->group(function () {
    //         Route::post('/', 'store');
    //         Route::put('/{bloque}', 'update');
    //         Route::delete('/{bloque}', 'destroy');
    //     });
    // });

});

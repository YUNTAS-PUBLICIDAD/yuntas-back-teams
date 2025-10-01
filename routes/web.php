<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExportController;

use App\Http\Controllers\Api\V1\Productos\ProductoController; 

Route::get('/exportProducto', [ExportController::class, 'exportProducto']);
Route::get('/exportBlog', [ExportController::class, 'exportBlog']);
Route::get('/exportCliente', [ExportController::class, 'exportCliente']);
Route::get('/exportReclamo', [ExportController::class, 'exportReclamo']);

Route::get('/', function () {
    return view('welcome');
});
Route::resource('productos', ProductoController::class);

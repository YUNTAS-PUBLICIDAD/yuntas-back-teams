<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\V1\Productos\ProductoController;

Route::get('/exportProducto', [ExportController::class, 'exportProducto']);
Route::get('/exportBlog', [ExportController::class, 'exportBlog']);
Route::get('/exportCliente', [ExportController::class, 'exportCliente']);
Route::get('/exportReclamo', [ExportController::class, 'exportReclamo']);

Route::get('/', function () {
    return view('welcome');
});
Route::resource('productos', ProductoController::class);

Route::get("/generate-link-simbolink", function(){
    if(file_exists(public_path("storage"))){
        return "existe";
    }

    Artisan::call("storage:link");
    app('files')->link(storage_path("app/public"), public_path("storage"));
    return "creado";
});

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostProducto\PostProducto;
use App\Http\Requests\PostProductoDetalle\PostProductoDetalle;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\ProductoDetalle;

class ProductoDetalleController extends BasicController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostProductoDetalle $request)
    {
        try {
            ProductoDetalle::create($request->all());
            return response()->json(['message' => 'Producto detalle creado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el producto detalle: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductoDetalle $productodetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductoDetalle $productodetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductoDetalle $productodetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductoDetalle $producto)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogDetalle;
use App\Http\Requests\PostBlogDetalle\PostBlogDetalle;
use App\Http\Controllers\Api\V1\BasicController;


class BlogDetalleController extends Controller
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
    public function store(BlogDetalle $request)
    {
        try {
            BlogDetalle::create($request->all());
            return response()->json(['message' => 'Blog detalle creado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el blog detalle: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogDetalle $blogdetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogDetalle $blogdetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogDetalle $blogdetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogDetalle $blogdetalle)
    {
        //
    }
}

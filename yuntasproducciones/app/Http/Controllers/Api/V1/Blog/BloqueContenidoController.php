<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Models\BloqueContenido;
use App\Http\Requests\StoreBloqueContenidoRequest;
use App\Http\Requests\UpdateBloqueContenidoRequest;

class BloqueContenidoController extends Controller
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
    public function store(StoreBloqueContenidoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BloqueContenido $bloqueContenido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloqueContenido $bloqueContenido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueContenidoRequest $request, BloqueContenido $bloqueContenido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueContenido $bloqueContenido)
    {
        //
    }
}

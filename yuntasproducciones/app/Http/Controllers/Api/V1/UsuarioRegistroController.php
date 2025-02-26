<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UsuarioRegistro;
use Illuminate\Http\Request;

class UsuarioRegistroController extends BasicController
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
    public function store(UsuarioRegistro $request)
    {
        try {
            UsuarioRegistro::create($request->all());
            return response()->json(['message' => 'Usuario registro creado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el usuario registro: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UsuarioRegistro $usuarioRegistro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsuarioRegistro $usuarioRegistro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsuarioRegistro $usuarioRegistro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsuarioRegistro $usuarioRegistro)
    {
        //
    }
}

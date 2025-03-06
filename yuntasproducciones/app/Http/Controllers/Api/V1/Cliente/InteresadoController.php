<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Http\Requests\StoreInteresadoRequest;
use App\Http\Requests\UpdateInteresadoRequest;
use App\Models\Interesado;
use App\Http\Controllers\Api\V1\BasicController;

class InteresadoController extends BasicController
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
    public function store(StoreInteresadoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Interesado $interesado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interesado $interesado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInteresadoRequest $request, Interesado $interesado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interesado $interesado)
    {
        //
    }
}

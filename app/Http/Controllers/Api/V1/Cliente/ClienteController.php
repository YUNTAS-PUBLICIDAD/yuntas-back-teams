<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Contains\HttpStatusCode;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Http\Requests\Cliente\UpdateClienteRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Clientes",
 *     description="API Endpoints para gestión de clientes"
 * )
 */
class ClienteController extends BasicController
{
    /**
     * Display a listing of clientes.
     * 
     * @OA\Get(
     *     path="/api/v1/clientes",
     *     tags={"Clientes"},
     *     summary="Obtener lista de todos los clientes",
     *     description="Retorna una lista de todos los clientes registrados",
     *     operationId="indexClientes",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de clientes obtenida correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Clientes listados correctamente."),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="celular", type="string", example="999888777"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al listar los clientes.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $clientes = Cliente::all();

            $message = $clientes->isEmpty() ? 'No hay clientes para listar.' : 'Clientes listados correctamente.';

            return $this->successResponse(
            $clientes,
            $message,
            HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un problema al listar los clientes. ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created cliente in storage.
     * 
     * @OA\Post(
     *     path="/api/v1/clientes",
     *     tags={"Clientes"},
     *     summary="Crear un nuevo cliente",
     *     description="Crea un nuevo registro de cliente con los datos proporcionados",
     *     operationId="storeCliente",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "celular"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="celular", type="string", example="999888777")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente creado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cliente registrado exitosamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al procesar la solicitud.")
     *         )
     *     )
     * )
     */
    public function store(StoreClienteRequest $request)
    {
        try {
            Cliente::create($request->all());

            return $this->successResponse(
                $request->all(),
                'Cliente registrado exitosamente.',
                HttpStatusCode::OK);
            
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrio un problema al procesar la solicitud. '. $e->getMessage()
            , HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *     path="/api/v1/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Obtener un cliente específico",
     *     description="Retorna los datos de un cliente según su ID",
     *     operationId="showCliente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del cliente a consultar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente encontrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cliente encontrado."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="celular", type="string", example="999888777"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cliente no encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al procesar la solicitud.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $cliente = Cliente::find($id);

            return $this->successResponse(
                $cliente,
                $cliente ? 'Cliente encontrado.' : 'Cliente no encontrado.', 
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified cliente in storage.
     * 
     * @OA\Put(
     *     path="/api/v1/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Actualizar un cliente existente",
     *     description="Actualiza los datos de un cliente según su ID",
     *     operationId="updateCliente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del cliente a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Updated"),
     *             @OA\Property(property="email", type="string", format="email", example="johnupdated@example.com"),
     *             @OA\Property(property="celular", type="string", example="999888777")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente actualizado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Se actualizaron los campos correctamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No se realizaron cambios",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="No se actualizaron los campos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cliente no encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al procesar la solicitud.")
     *         )
     *     )
     * )
     */
    public function update(UpdateClienteRequest $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->update($request->validated());

            $message = $cliente->wasChanged() 
                ? 'Se actualizaron los campos correctamente.' 
                : 'No se actualizaron los campos';

            $statusCode = $cliente->wasChanged() 
                ? HttpStatusCode::OK 
                : HttpStatusCode::NO_CONTENT;

            return $this->successResponse($cliente, $message, $statusCode);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Cliente no encontrado.', HttpStatusCode::NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified cliente from storage.
     * 
     * @OA\Delete(
     *     path="/api/v1/clientes/{id}",
     *     tags={"Clientes"},
     *     summary="Eliminar un cliente",
     *     description="Elimina un cliente según su ID",
     *     operationId="destroyCliente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del cliente a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Se eliminó correctamente el cliente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cliente no encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al procesar la solicitud.")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            
            if (!$cliente) {
                return $this->errorResponse('Cliente no encontrado.', 
                HttpStatusCode::NOT_FOUND);
            }

            if (!$cliente->delete()) {
                return $this->errorResponse('No se pudo eliminar el cliente.', 
                HttpStatusCode::INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse(null, 'Se eliminó correctamente el cliente.', 
            HttpStatusCode::OK);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Cliente no encontrado.', 
            HttpStatusCode::NOT_FOUND);
        } catch(\Exception $e) {
            return $this->errorResponse('Ocurrió un problema al procesar la solicitud. ' . $e->getMessage(), 
            HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}

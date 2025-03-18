<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Contains\HttpStatusCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BasicController extends Controller
{
    /**
     * Respuesta de éxito con datos
     */
    protected function successResponse(mixed $data, string $message = 'Operación exitosa', HttpStatusCode 
    $status = HttpStatusCode::OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status->value);
    }

    /**
     * Respuesta de éxito sin contenido
     */
    protected function noContentResponse(string $message = 'Operación exitosa'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => null
        ], HttpStatusCode::NO_CONTENT->value);
    }

    /**
     * Respuesta de error
     */
    protected function errorResponse(string $message, HttpStatusCode $status = 
    HttpStatusCode::BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status->value);
    }

    /**
     * Respuesta de error 401 (No autorizado)
     */
    protected function unauthorizedResponse(string $message = 'No autorizado'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::UNAUTHORIZED);
    }

    /**
     * Respuesta de error 403 (Prohibido)
     */
    protected function forbiddenResponse(string $message = 'Acceso denegado'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::FORBIDDEN);
    }

    /**
     * Respuesta de error 404 (No encontrado)
     */
    protected function notFoundResponse(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::NOT_FOUND);
    }

    /**
     * Respuesta de error 405 (Método no permitido)
     */
    protected function methodNotAllowedResponse(string $message = 'Método no permitido'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::METHOD_NOT_ALLOWED);
    }

    /**
     * Respuesta de error 422 (Contenido no procesable)
     */
    protected function unprocessableContentResponse(string $message = 'Solicitud no procesable', 
    mixed $errors = null): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::METHOD_UNPROCESSABLE_CONTENT, $errors);
    }

    /**
     * Respuesta de error 429 (Demasiadas solicitudes)
     */
    protected function tooManyRequestsResponse(string $message = 'Demasiadas solicitudes'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::MANY_REQUESTS);
    }

    /**
     * Respuesta de error 500 (Error interno del servidor)
     */
    protected function internalServerErrorResponse(string $message = 'Error interno del servidor'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}

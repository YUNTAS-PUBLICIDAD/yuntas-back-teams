<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use App\Http\Contains\HttpStatusCode;

class ApiResponseService
{
    /**
     * Respuesta de éxito con datos
     */
    public function successResponse(mixed $data, string $message = 'Operación exitosa', HttpStatusCode $status = HttpStatusCode::OK): JsonResponse
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
    public function noContentResponse(string $message = 'Operación exitosa'): JsonResponse
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
    public function errorResponse(string $message, HttpStatusCode $status = HttpStatusCode::BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status->value);
    }

    /**
     * Respuestas de error específicas
     */
    public function unauthorizedResponse(string $message = 'No autorizado'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::UNAUTHORIZED);
    }

    public function forbiddenResponse(string $message = 'Acceso denegado'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::FORBIDDEN);
    }

    public function notFoundResponse(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::NOT_FOUND);
    }

    public function methodNotAllowedResponse(string $message = 'Método no permitido'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::METHOD_NOT_ALLOWED);
    }

    public function unprocessableContentResponse(string $message = 'Solicitud no procesable', mixed $errors = null): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::METHOD_UNPROCESSABLE_CONTENT, $errors);
    }

    public function tooManyRequestsResponse(string $message = 'Demasiadas solicitudes'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::MANY_REQUESTS);
    }

    public function internalServerErrorResponse(string $message = 'Error interno del servidor'): JsonResponse
    {
        return $this->errorResponse($message, HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}

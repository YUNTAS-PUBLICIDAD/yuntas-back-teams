<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Contains\HttpStatusCode;
use App\Services\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BasicController extends Controller
{
    protected $apiResponseService;

    // Quitar la inyección del constructor y usar lazy loading
    public function __construct()
    {
        // Constructor vacío - la inyección se hace bajo demanda
    }

    /**
     * Obtener ApiResponseService de forma lazy
     */
    protected function getApiResponseService(): ApiResponseService
    {
        if (!$this->apiResponseService) {
            $this->apiResponseService = app(ApiResponseService::class);
        }
        return $this->apiResponseService;
    }

    /**
     * Respuesta de éxito con datos
     */
    protected function successResponse(mixed $data, string $message = 'Operación exitosa', HttpStatusCode 
    $status = HttpStatusCode::OK): JsonResponse
    {
        return $this->getApiResponseService()->successResponse($data, $message, $status);
    }

    /**
     * Respuesta de éxito sin contenido
     */
    protected function noContentResponse(string $message = 'Operación exitosa'): JsonResponse
    {
        return $this->getApiResponseService()->noContentResponse($message);
    }

    /**
     * Respuesta de éxito sin contenido (204 No Content)
     */
    protected function successNoContentResponse(string $message = 'Operación exitosa'): JsonResponse
    {
        return $this->getApiResponseService()->successNoContentResponse($message);
    }

    /**
     * Respuesta de error
     */
    protected function errorResponse(string $message, HttpStatusCode $status = 
    HttpStatusCode::BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return $this->getApiResponseService()->errorResponse($message, $status, $errors);
    }

    /**
     * Respuesta de error 401 (No autorizado)
     */
    protected function unauthorizedResponse(string $message = 'No autorizado'): JsonResponse
    {
        return $this->getApiResponseService()->unauthorizedResponse($message);
    }

    /**
     * Respuesta de error 403 (Prohibido)
     */
    protected function forbiddenResponse(string $message = 'Acceso denegado'): JsonResponse
    {
        return $this->getApiResponseService()->forbiddenResponse($message);
    }

    /**
     * Respuesta de error 404 (No encontrado)
     */
    protected function notFoundResponse(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->getApiResponseService()->notFoundResponse($message);
    }

    /**
     * Respuesta de error 405 (Método no permitido)
     */
    protected function methodNotAllowedResponse(string $message = 'Método no permitido'): JsonResponse
    {
        return $this->getApiResponseService()->methodNotAllowedResponse($message);
    }

    /**
     * Respuesta de error 422 (Contenido no procesable)
     */
    protected function unprocessableContentResponse(string $message = 'Solicitud no procesable', 
    mixed $errors = null): JsonResponse
    {
        return $this->getApiResponseService()->unprocessableContentResponse($message, $errors);
    }

    /**
     * Respuesta de error 429 (Demasiadas solicitudes)
     */
    protected function tooManyRequestsResponse(string $message = 'Demasiadas solicitudes'): JsonResponse
    {
        return $this->getApiResponseService()->tooManyRequestsResponse($message);
    }

    /**
     * Respuesta de error 500 (Error interno del servidor)
     */
    protected function internalServerErrorResponse(string $message = 'Error interno del servidor'): JsonResponse
    {
        return $this->getApiResponseService()->internalServerErrorResponse($message);
    }
}
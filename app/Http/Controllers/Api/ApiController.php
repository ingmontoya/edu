<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class ApiController extends Controller
{
    /**
     * Respuesta exitosa con datos
     */
    protected function success(
        mixed $data = null, 
        string $message = 'Operación exitosa', 
        int $code = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Respuesta exitosa de creación
     */
    protected function created(mixed $data, string $message = 'Recurso creado exitosamente'): JsonResponse
    {
        return $this->success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Respuesta de error
     */
    protected function error(
        string $message = 'Error en la operación', 
        int $code = Response::HTTP_BAD_REQUEST,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Respuesta de no encontrado
     */
    protected function notFound(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Respuesta de no autorizado
     */
    protected function unauthorized(string $message = 'No autorizado'): JsonResponse
    {
        return $this->error($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Respuesta de prohibido
     */
    protected function forbidden(string $message = 'Acceso denegado'): JsonResponse
    {
        return $this->error($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Respuesta de validación fallida
     */
    protected function validationError(array $errors, string $message = 'Error de validación'): JsonResponse
    {
        return $this->error($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Respuesta paginada
     */
    protected function paginated($paginator, string $message = 'Datos obtenidos exitosamente'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ]);
    }

    /**
     * Respuesta sin contenido
     */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

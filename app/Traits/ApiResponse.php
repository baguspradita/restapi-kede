<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse($message = 'Error', $errors = [], $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function paginatedResponse($data, $message = 'Success')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'page' => $data->currentPage(),
                'limit' => $data->perPage(),
                'total' => $data->total(),
                'totalPages' => $data->lastPage(),
            ],
        ]);
    }

    protected function createdResponse($data = null, $message = 'Created successfully')
    {
        return $this->successResponse($data, $message, 201);
    }

    protected function notFoundResponse($message = 'Resource not found')
    {
        return $this->errorResponse($message, [], 404);
    }

    protected function validationErrorResponse($errors, $message = 'Validation error')
    {
        return $this->errorResponse($message, $errors, 422);
    }

    protected function unauthorizedResponse($message = 'Unauthorized')
    {
        return $this->errorResponse($message, [], 401);
    }

    protected function forbiddenResponse($message = 'Forbidden')
    {
        return $this->errorResponse($message, [], 403);
    }
}

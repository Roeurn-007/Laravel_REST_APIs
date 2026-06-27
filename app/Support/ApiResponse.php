<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponse
{
    protected function successResponse(string $message, mixed $data = null, array $meta = [], int $status = 200): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function resourceResponse(string $message, JsonResource|AnonymousResourceCollection $resource, int $status = 200): JsonResponse
    {
        $payload = $resource->response()->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $payload['data'] ?? null,
            'meta' => $payload['meta'] ?? [],
            'links' => $payload['links'] ?? [],
        ], $status);
    }

    protected function errorResponse(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== []) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}

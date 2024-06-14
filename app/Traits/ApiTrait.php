<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiTrait
{
    public function successResponse(float|null $value, int $statusCode = 200): JsonResponse
    {
        return response()->json(
            $value,
            $statusCode,
            [
                'Content-Type' => 'application/json',
            ],
            JSON_NUMERIC_CHECK
        );
    }
}

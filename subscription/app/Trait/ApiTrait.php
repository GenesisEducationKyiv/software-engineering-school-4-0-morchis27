<?php

namespace App\Trait;

use Illuminate\Http\JsonResponse;

trait ApiTrait
{
    /**
     * @param float|array<string, mixed>|null $value
     * @param int $statusCode
     * @return JsonResponse
     */
    public function successResponse(float|array|bool|null $value, int $statusCode = 200): JsonResponse
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

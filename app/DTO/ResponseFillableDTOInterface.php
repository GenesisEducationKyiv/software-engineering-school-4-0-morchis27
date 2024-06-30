<?php

namespace App\DTO;

use Illuminate\Http\Request;

interface ResponseFillableDTOInterface
{
    /**
     * @param array<string, mixed> $responseData
     * @return void
     */
    public function fillByResponse(array $responseData): void;
}

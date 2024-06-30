<?php

namespace App\DTO;

use Illuminate\Http\Request;

interface ArrayableInterface
{
    /**
     * @return array<string, int|string|bool>
     */
    public function toArray(): array;
}

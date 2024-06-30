<?php

namespace App\DTO;

use Illuminate\Http\Request;

interface RequestFillableDTOInterface
{
    public function fillByRequest(Request $request): void;
}

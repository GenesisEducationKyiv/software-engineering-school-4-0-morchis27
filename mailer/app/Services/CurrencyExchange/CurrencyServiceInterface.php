<?php

namespace App\Services\CurrencyExchange;

interface CurrencyServiceInterface
{
    public function getRate(): float;
}

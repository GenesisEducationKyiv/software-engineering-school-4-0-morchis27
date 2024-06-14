<?php

namespace App\DTO\ExchangeRateDTO;

class ExchangeRateDTO
{
    public function __construct(
        private float $exchangeRate,
    ) {
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }
}

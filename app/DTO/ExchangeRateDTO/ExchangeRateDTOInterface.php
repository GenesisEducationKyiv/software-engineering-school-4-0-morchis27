<?php

namespace App\DTO\ExchangeRateDTO;

interface ExchangeRateDTOInterface
{
    public function getSellExchangeRate(): float;

    public function getBuyExchangeRate(): float;
}

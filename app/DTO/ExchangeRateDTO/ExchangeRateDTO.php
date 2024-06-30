<?php

namespace App\DTO\ExchangeRateDTO;

class ExchangeRateDTO implements ExchangeRateDTOInterface
{
    private float $sellRate;
    private float $buyRate;

    /**
     * @param array<string, float> $exchangeRates
     */
    public function __construct(
        array $exchangeRates,
    ) {
        $this->sellRate = $exchangeRates['sale'];
        $this->buyRate = $exchangeRates['buy'];
    }

    public function getSellExchangeRate(): float
    {
        return $this->sellRate;
    }

    public function getBuyExchangeRate(): float
    {
        return $this->buyRate;
    }
}

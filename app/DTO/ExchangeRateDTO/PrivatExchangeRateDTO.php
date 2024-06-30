<?php

namespace App\DTO\ExchangeRateDTO;

class PrivatExchangeRateDTO implements ExchangeRateDTOInterface
{
    private float $sellRate;
    private float $buyRate;

    /**
     * @param array<string, float> $exchangeRates
     */
    public function __construct(
        private array $exchangeRates,
    ) {
        $this->sellRate = $this->exchangeRates['sale'];
        $this->buyRate = $this->exchangeRates['buy'];
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

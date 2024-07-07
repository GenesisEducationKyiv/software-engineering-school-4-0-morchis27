<?php

namespace App\DTO\ExchangeRateDTO;

use App\Enum\Currency;

class CurrencyBeaconExchangeRateDTO implements ExchangeRateDTOInterface
{
    private float $sellRate;
    private float $buyRate;

    /**
     * @param array<string, float> $exchangeRates
     */
    public function __construct(
        private array $exchangeRates,
    ) {
        $this->sellRate = $this->buyRate = $this->exchangeRates[Currency::UAH->value];
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

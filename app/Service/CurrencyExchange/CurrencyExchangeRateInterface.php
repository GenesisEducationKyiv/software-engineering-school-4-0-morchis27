<?php

namespace App\Service\CurrencyExchange;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;

interface CurrencyExchangeRateInterface
{
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ExchangeRateDTOInterface;
}

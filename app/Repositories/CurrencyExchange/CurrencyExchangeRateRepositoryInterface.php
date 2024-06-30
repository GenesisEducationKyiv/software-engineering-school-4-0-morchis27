<?php

namespace App\Repositories\CurrencyExchange;

use App\DTO\ExchangeRateDTO\ExchangeRateDTOInterface;
use App\Enum\Currency;

interface CurrencyExchangeRateRepositoryInterface
{
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ExchangeRateDTOInterface;
}

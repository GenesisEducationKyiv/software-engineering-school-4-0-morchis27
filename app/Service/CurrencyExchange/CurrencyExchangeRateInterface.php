<?php

namespace App\Service\CurrencyExchange;

use App\Enum\Currency;

interface CurrencyExchangeRateInterface
{
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): float;
}

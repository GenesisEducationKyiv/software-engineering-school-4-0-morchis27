<?php

namespace App\Service\CurrencyExchange\Repository;

use App\Enum\Currency;

interface CurrencyExchangeRateRepositoryInterface
{
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): float;
}

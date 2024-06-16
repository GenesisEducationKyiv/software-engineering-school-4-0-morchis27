<?php

namespace App\Service\CurrencyExchange\Repository;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Enum\Currency;

interface CurrencyExchangeRateRepositoryInterface
{
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ExchangeRateDTO;
}

<?php

namespace App\Service\CurrencyExchange\Repository;

use App\Enum\Currencies;

interface CurrencyExchangeRateRepositoryInterface
{
    public function getCurrentRate(Currencies $currencyFrom, Currencies $currencyTo): float;

    //potentially different methods for different use cases, for example historical data
//    public function getRatesByPeriod(Currencies $currencyFrom, Currencies $currencyTo, Carbon $dateFrom, Carbon $dateTo): array;
}

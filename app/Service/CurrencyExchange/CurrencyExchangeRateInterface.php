<?php

namespace App\Service\CurrencyExchange;

use App\Enum\Currencies;

interface CurrencyExchangeRateInterface
{
    public function getCurrentRate(Currencies $currencyFrom, Currencies $currencyTo): float;

//    public function getRatesByPeriod(
//        Currencies $currencyFrom,
//        Currencies $currencyTo,
//        Carbon $dateFrom,
//        Carbon $dateTo
//    ): array;
}

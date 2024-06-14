<?php

namespace App\Service\CurrencyExchange\Repository;

use App\Enum\Currency;
use Nette\NotImplementedException;

class DBCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): float
    {
        throw new NotImplementedException('use of not implemented DBCurrencyExchangeRateRepository::getCurrentRate()');
    }
}

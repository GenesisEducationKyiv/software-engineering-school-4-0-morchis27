<?php

namespace App\Service\CurrencyExchange\Repository;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Enum\Currency;
use Nette\NotImplementedException;

class DBCurrencyExchangeRateRepository implements CurrencyExchangeRateRepositoryInterface
{
    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ExchangeRateDTO
    {
        throw new NotImplementedException('use of not implemented DBCurrencyExchangeRateRepository::getCurrentRate()');
    }
}

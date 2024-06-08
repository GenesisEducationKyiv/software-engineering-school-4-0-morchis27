<?php

namespace App\Service\CurrencyExchange;

use App\Enum\Currencies;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use App\Service\CurrencyExchange\Repository\DBCurrencyExchangeRateRepository;

class CurrencyExchangeRateService implements CurrencyExchangeRateInterface
{
    public function __construct(
        private CurrencyExchangeRateRepositoryInterface $repository,
    ) {
    }

    public function getCurrentRate(Currencies $currencyFrom, Currencies $currencyTo): float
    {
        $rate = $this->repository->getCurrentRate($currencyFrom, $currencyTo);

        return $rate;
    }
}

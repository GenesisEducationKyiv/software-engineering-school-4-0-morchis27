<?php

namespace App\Service\CurrencyExchange;

use App\DTO\ExchangeRateDTO\ExchangeRateDTO;
use App\Enum\Currency;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;

class CurrencyExchangeRateService implements CurrencyExchangeRateInterface
{
    public function __construct(
        private CurrencyExchangeRateRepositoryInterface $repository,
    ) {
    }

    public function getCurrentRate(Currency $currencyFrom, Currency $currencyTo): ExchangeRateDTO
    {
        $rate = $this->repository->getCurrentRate($currencyFrom, $currencyTo);

        return $rate;
    }
}

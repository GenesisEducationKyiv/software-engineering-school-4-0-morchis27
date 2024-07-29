<?php

namespace App\Repository\CurrencyExchange\RepositoryCreator;

use App\Repository\CurrencyExchange\CurrencyExchangeRateRepositoryInterface;

interface CurrencyExchangeRateRepositoryCreatorInterface
{
    public function create(): CurrencyExchangeRateRepositoryInterface;
}

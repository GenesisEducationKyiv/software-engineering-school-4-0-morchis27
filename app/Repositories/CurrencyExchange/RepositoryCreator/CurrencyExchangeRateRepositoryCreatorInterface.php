<?php

namespace App\Repositories\CurrencyExchange\RepositoryCreator;

use App\Repositories\CurrencyExchange\CurrencyExchangeRateRepositoryInterface;

interface CurrencyExchangeRateRepositoryCreatorInterface
{
    public function create(): CurrencyExchangeRateRepositoryInterface;
}

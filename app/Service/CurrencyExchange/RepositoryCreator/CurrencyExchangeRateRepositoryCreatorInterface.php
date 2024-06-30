<?php

namespace App\Service\CurrencyExchange\RepositoryCreator;

use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;

interface CurrencyExchangeRateRepositoryCreatorInterface
{
    public function create(): CurrencyExchangeRateRepositoryInterface;
}

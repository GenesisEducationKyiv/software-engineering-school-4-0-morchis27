<?php

namespace App\Providers;

use App\Service\CurrencyExchange\CurrencyExchangeRateInterface;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use App\Service\CurrencyExchange\Repository\ApiLayerCurrencyExchangeRateRepository;
use App\Service\CurrencyExchange\Repository\CurrencyExchangeRateRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CurrencyExchangeRateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CurrencyExchangeRateRepositoryInterface::class, ApiLayerCurrencyExchangeRateRepository::class);
        $this->app->bind(CurrencyExchangeRateInterface::class, CurrencyExchangeRateService::class);
    }
}

<?php

namespace App\Providers;

use App\Handlers\CurrencyExchange\ApiLayerHandler;
use App\Handlers\CurrencyExchange\CurrencyBeaconHandler;
use App\Handlers\CurrencyExchange\PrivatHandler;
use App\Service\CurrencyExchange\CurrencyExchangeRateInterface;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use App\Service\CurrencyExchange\RepositoryCreator\ApiLayerCurrencyExchangeRateRepositoryCreator;
use App\Service\CurrencyExchange\RepositoryCreator\CurrencyBeaconCurrencyExchangeRateRepositoryCreator;
use App\Service\CurrencyExchange\RepositoryCreator\CurrencyExchangeRateRepositoryCreatorInterface;
use App\Service\CurrencyExchange\RepositoryCreator\PrivatCurrencyExchangeRateRepositoryCreator;
use Illuminate\Support\ServiceProvider;

class CurrencyExchangeRateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CurrencyExchangeRateInterface::class, CurrencyExchangeRateService::class);

        $this->app->when(PrivatHandler::class)
            ->needs(CurrencyExchangeRateRepositoryCreatorInterface::class)
            ->give(PrivatCurrencyExchangeRateRepositoryCreator::class);
        $this->app->when(CurrencyBeaconHandler::class)
            ->needs(CurrencyExchangeRateRepositoryCreatorInterface::class)
            ->give(CurrencyBeaconCurrencyExchangeRateRepositoryCreator::class);
        $this->app->when(ApiLayerHandler::class)
            ->needs(CurrencyExchangeRateRepositoryCreatorInterface::class)
            ->give(ApiLayerCurrencyExchangeRateRepositoryCreator::class);
    }
}

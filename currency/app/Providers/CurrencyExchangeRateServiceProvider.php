<?php

namespace App\Providers;


use App\Handlers\CurrencyExchange\ApiLayerHandler;
use App\Handlers\CurrencyExchange\CurrencyBeaconHandler;
use App\Handlers\CurrencyExchange\PrivatHandler;
use App\Repository\CurrencyExchange\RepositoryCreator\ApiLayerCurrencyExchangeRateRepositoryCreator;
use App\Repository\CurrencyExchange\RepositoryCreator\CurrencyBeaconCurrencyExchangeRateRepositoryCreator;
use App\Repository\CurrencyExchange\RepositoryCreator\CurrencyExchangeRateRepositoryCreatorInterface;
use App\Repository\CurrencyExchange\RepositoryCreator\PrivatCurrencyExchangeRateRepositoryCreator;
use App\Service\CurrencyExchange\CurrencyExchangeRateInterface;
use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CurrencyExchangeRateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CurrencyExchangeRateInterface::class, CurrencyExchangeRateService::class);
        $this->app->when(CurrencyExchangeRateService::class)
            ->needs('$handlers')
            ->give(function (Application $app) {
                return [
                    $app->make(PrivatHandler::class),
                    $app->make(CurrencyBeaconHandler::class),
                    $app->make(ApiLayerHandler::class),
                ];
            });

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

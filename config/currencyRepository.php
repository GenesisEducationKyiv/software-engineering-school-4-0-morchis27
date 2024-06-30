<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the binding of implementations for the third party
    | api of currency exchange.
    |
    */

    'pointer' => env('CURRENCY_EXCHANGE_REPOSITORY', 'apiLayer'),

    'repositories' => [
        'apiLayer' => [
            'creator' => \App\Service\CurrencyExchange\RepositoryCreator\ApiLayerCurrencyExchangeRateRepositoryCreator::class,
            'exchangeServiceApiKey' => env('EXCHANGE_SERVICE_API_KEY'),
            'exchangeServiceApiHost' => env('EXCHANGE_SERVICE_API_HOST'),
        ],
        'currencyBeacon' => [
            'creator' => \App\Service\CurrencyExchange\RepositoryCreator\CurrencyBeaconCurrencyExchangeRateRepositoryCreator::class,
            'exchangeServiceApiKey' => env('CURRENCY_BEACON_EXCHANGE_SERVICE_API_KEY'),
            'exchangeServiceApiHost' => env('CURRENCY_BEACON_EXCHANGE_SERVICE_API_HOST'),
        ],
        'privat' => [
            'creator' => \App\Service\CurrencyExchange\RepositoryCreator\PrivatCurrencyExchangeRateRepositoryCreator::class,
            'exchangeServiceApiKey' => env('PRIVAT_EXCHANGE_SERVICE_API_KEY', null),
            'exchangeServiceApiHost' => env('PRIVAT_EXCHANGE_SERVICE_API_HOST'),
        ],
    ],
];

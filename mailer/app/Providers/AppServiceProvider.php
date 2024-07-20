<?php

namespace App\Providers;

use App\Service\CurrencyExchange\CurrencyExchangeRateService;
use App\Services\CurrencyExchange\CurrencyService;
use App\Services\CurrencyExchange\CurrencyServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CurrencyServiceInterface::class, CurrencyService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

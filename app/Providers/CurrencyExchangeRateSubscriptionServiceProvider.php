<?php

namespace App\Providers;

use App\Service\Subscription\SubscriptionInterface;
use App\Service\Subscription\SubscriptionService;
use Illuminate\Support\ServiceProvider;

class CurrencyExchangeRateSubscriptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriptionInterface::class, SubscriptionService::class);
    }
}

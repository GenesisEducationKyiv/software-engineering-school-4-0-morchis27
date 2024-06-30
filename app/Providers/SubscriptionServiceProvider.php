<?php

namespace App\Providers;

use App\Service\Subscription\SubscriptionService;
use App\Service\Subscription\SubscriptionServiceInterface;
use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);
    }
}

<?php

namespace App\Providers;

use App\Repository\Subscriber\SubscriberRepository;
use App\Repository\Subscriber\SubscriberRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberRepository::class);
    }
}

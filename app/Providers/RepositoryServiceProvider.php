<?php

namespace App\Providers;

use App\Repositories\Subscriber\SubscriberRepository;
use App\Repositories\Subscriber\SubscriberRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberRepository::class);
    }
}

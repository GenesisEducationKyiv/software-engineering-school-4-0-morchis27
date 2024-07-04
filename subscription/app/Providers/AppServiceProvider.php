<?php

namespace App\Providers;

use App\Listeners\HandleSubscribedListener;
use App\Service\Currency\CurrencyService;
use App\Service\Currency\CurrencyServiceInterface;
use App\Service\MessageBroker\KafkaMessageBroker;
use App\Service\MessageBroker\MessageBrokerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(HandleSubscribedListener::class)->needs('$shouldBeVerified')
            ->give(function () {
                return (bool) config('app.shouldBeVerified');
            });
        $this->app->bind(MessageBrokerInterface::class, KafkaMessageBroker::class);
        $this->app->when(KafkaMessageBroker::class)->needs('$kafkaHost')
            ->give(function ($app) {
                /** @phpstan-ignore-next-line */
                return (string) config('kafka.brokers');
            });
        $this->app->when(KafkaMessageBroker::class)->needs('$topic')
            ->give(function ($app) {
                /** @phpstan-ignore-next-line */
                return (string) config('kafka.topics.email');
            });
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

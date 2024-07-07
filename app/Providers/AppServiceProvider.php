<?php

namespace App\Providers;

use App\Listeners\HandleSubscribedListener;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

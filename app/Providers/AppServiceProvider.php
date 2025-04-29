<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RecurringTrajetService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RecurringTrajetService::class, function ($app) {
            return new RecurringTrajetService();
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

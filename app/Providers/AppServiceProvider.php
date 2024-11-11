<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ChatAppointmentSheetsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ChatAppointmentSheetsService::class, function ($app) {
            return new ChatAppointmentSheetsService();
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

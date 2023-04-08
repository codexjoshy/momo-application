<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Termi\TermiiSmsService;
use App\Services\Contracts\SmsServiceInterface;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SmsServiceInterface::class, TermiiSmsService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

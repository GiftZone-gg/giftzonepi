<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Força HTTPS em produção
        if ($this->app->environment('production') || str_contains(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
    }
}
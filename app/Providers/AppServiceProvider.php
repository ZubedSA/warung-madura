<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap pagination (optional)
        // Paginator::useBootstrap();

        // Set locale
        setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_Indonesia.1252');

        // Carbon locale
        \Carbon\Carbon::setLocale('id');

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}

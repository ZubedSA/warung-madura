<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Turso\Http\Laravel\Database\LibSQLConnector;
use Turso\Http\Laravel\Database\LibSQLConnection;

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

        if ($this->app->environment('production') || isset($_SERVER['VERCEL'])) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Manually register the libsql driver to ensure it's always available
        DB::extend('libsql', function ($config, $name) {
            $connector = new LibSQLConnector();
            $db = $connector->connect($config);
            return new LibSQLConnection($db, $config['database'] ?? 'warung_madura', $config['prefix'] ?? '', $config);
        });
    }
}

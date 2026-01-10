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

        // Manually register the libsql driver with HARDCODED config for absolute reliability on Vercel
        DB::extend('libsql', function ($config, $name) {
            $hardcodedConfig = [
                'driver' => 'libsql',
                'url' => 'https://turso-db-create-warung-madura-zubedsa.aws-ap-northeast-1.turso.io',
                'authToken' => 'eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3Njc5ODk2MDIsImlkIjoiOTVlNWM3MGUtZTU4YS00MDFjLWI5Y2MtNWM3ZWMxNWZkZTUwIiwicmlkIjoiZjM1NTFhNWEtNjg2OS00MDc1LThhYTAtMjEyYjI5MDBhMDkzIn0.CoBmITRsgBq1jWm6WfFLf3AaEwokwPScM6cNbLZOgwZ7_GuuT7S7Q7r0D95e2oWxe6VrAPC3hsLRM-hsY51sAQ',
                'database' => 'warung_madura',
                'prefix' => '',
            ];
            $connector = new LibSQLConnector();
            $db = $connector->connect($hardcodedConfig);
            return new LibSQLConnection($db, 'warung_madura', '', $hardcodedConfig);
        });
    }
}

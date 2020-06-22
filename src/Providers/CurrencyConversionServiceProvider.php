<?php

namespace EZ\CurrencyConversion\Providers;

use Illuminate\Support\ServiceProvider;
use EZ\CurrencyConversion\Services\CurrencyConversionService;

class CurrencyConversionServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the currency conversion service
        $this->app->singleton("currency-conversion", function($app) {
            return new CurrencyConversionService();
        });

        // Setup loading of the migrations
        $this->loadMigrationsFrom(__DIR__."/../database/migrations");

        // Setup loading of the config file
        $this->mergeConfigFrom(__DIR__."/../config/currency-conversion.php", "currency-conversion");
    }

    public function boot()
    {
        // Setup publishing of the config file
        $this->publishes([
            __DIR__."/../config/currency-conversion.php" => config_path("currency-conversion.php"),
        ], "config");

        // Setup publishing of the migrations & seeds
        $this->publishes([
            __DIR__."/../database/migrations/create_currencies_table.php" => database_path('migrations/' . date('Y_m_d_His', time())  . '_create_currencies_table.php'),
            __DIR__."/../database/seeds/CurrencySeeder.php" => database_path("seeds/CurrencySeeder.php"),
        ], "database");
        

    }
}
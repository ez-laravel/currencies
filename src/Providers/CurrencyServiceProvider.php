<?php

namespace EZ\Currencies\Providers;

use Illuminate\Support\ServiceProvider;
use EZ\CurrencyConversion\Services\CurrencyService;

class CurrenciesProvider extends ServiceProvider
{
    public function register()
    {
        // Register the currency conversion service
        $this->app->singleton("currencies", function($app) {
            return new CurrencyService();
        });

        // Setup loading of the migrations
        $this->loadMigrationsFrom(__DIR__."/../database/migrations");

        // Setup loading of the config file
        $this->mergeConfigFrom(__DIR__."/../config/currencies.php", "currencies");
    }

    public function boot()
    {
        // Setup publishing of the config file
        $this->publishes([
            __DIR__."/../config/currencies.php" => config_path("currencies.php"),
        ], "config");

        // Setup publishing of the migrations & seeds
        $this->publishes([
            __DIR__."/../database/migrations/create_currencies_table.php" => database_path('migrations/' . date('Y_m_d_His', time())  . '_create_currencies_table.php'),
            __DIR__."/../database/seeds/CurrencySeeder.php" => database_path("seeds/CurrencySeeder.php"),
        ], "database");


    }
}
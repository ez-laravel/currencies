<?php

use EZ\Currencies\Models\Currency;

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("currencies")->delete();

        // Euro
        Currency::create([
            "code" => "EUR",
            "name" => "Euro",
            "symbol" => "€",
            "html_code" => "&#8374;",
            "html_entity" => "&euro;",
        ]);
        
        // US Dollar
        Currency::create([
            "code" => "USD",
            "name" => "United States Dollar",
            "symbol" => "$",
            "html_code" => "&#36;",
            "html_entity" => "&dollar;"
        ]);
        
        // Pound
        Currency::create([
            "code" => "GBP",
            "name" => "Pound Sterling",
            "symbol" => "£",
            "html_code" => "&#163;",
            "html_entity" => "&pound;"
        ]);
        
        // Yen
        Currency::create([
            "code" => "JPY",
            "name" => "Japanese Yen",
            "symbol" => "¥",
            "html_code" => "&#165;",
            "html_entity" => "&yen;"
        ]);
        
        // app("currencies")->updateConversionRates();
    }
}
<?php

namespace EZ\Currencies\Services\Api;

use EZ\Currencies\Models\Currency;
use EZ\Currencies\Contracts\CurrencyRateApi;

class RatesApi implements CurrencyRateApi
{
    public function __construct()
    {
        
    }

    public function getConversionRatesFor(Currency $currency, array $currencies)
    {

    }
}
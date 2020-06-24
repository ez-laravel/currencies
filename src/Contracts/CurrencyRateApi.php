<?php

namespace EZ\Currencies\Contracts;

use EZ\Currencies\Models\Currency;
use Illuminate\Support\Collection;

interface CurrencyRateApi
{
    public function getConversionRatesFor(Currency $currency, Collection $currencies);
}
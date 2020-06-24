<?php

namespace EZ\Currencies\Services\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use EZ\Currencies\Models\Currency;
use EZ\Currencies\Contracts\CurrencyRateApi;

class ExchangeRatesApi implements CurrencyRateApi
{
    public function getConversionRatesFor(Currency $currency, Collection $currencies)
    {
        // Compose target currency symbols
        $targets = $this->extractSymbols($currencies, $currency);

        // Compose the API endpoint we'll be calling
        $endpoint = "https://api.exchangeratesapi.io/latest?base=".$currency->code."&symbols=".$targets;

        // Use guzzle to make a HTTP request to the API endpoint
        $client = new Client();
        $result = $client->get($endpoint);
        $response = json_decode($result->getBody()->getContents());

        // If something went wrong return false
        if ($result->getStatusCode() !== 200) return false;

        // Otherwise return the retrieved rates as an array
        $data = (array) $response->rates;

        // Add the conversion rate for itself (since this API does not return it)
        $data[$currency->code] = 1;

        // Return the data
        return $data;
    }
    
    private function extractSymbols(Collection $currencies, Currency $exclude = null)
    {   
        // Collect the currency codes of all currencies other than the base currency
        $codes = [];

        // Process all the available currencies
        foreach ($currencies as $currency)
        {
            // If this is the base currency, skip it
            if (!is_null($exclude) && $exclude->id == $currency->id) continue;

            // Add the currency code to the list of codes
            $codes[] = $currency->code;
        }

        // Return the imploded list of currency codes seperated by a comma
        return implode(",", $codes);
    }
}
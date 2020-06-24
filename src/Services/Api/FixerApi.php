<?php

namespace EZ\Currencies\Services\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use EZ\Currencies\Models\Currency;
use EZ\Currencies\Contracts\CurrencyRateApi;

class FixerApi implements CurrencyRateApi
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config("currencies.conversion_rates.api_keys.fixer");
    }

    public function getConversionRatesFor(Currency $currency, Collection $currencies)
    {
        // Compose the string indicating the target currencies we want the exchange rate from using the $currency as it's base
        $targets = $this->extractSymbols($currencies, $currency);

        // Compose the API endpoint we'll be calling
        $endpoint = "https://data.fixer.io/api/latest?access_key=".$this->apiKey."&base=".$currency->code."&symbols=".$targets;

        // Use guzzle to make a HTTP request to the API endpoint
        $client = new Client();
        $result = $client->get($endpoint);
        $response = json_decode($result->getBody()->getContents());

        // If something went wrong return false
        if (!$response->success) return false;

        // Otherwise return the retrieved rates as an array
        return (array) $response->rates;
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
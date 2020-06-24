<?php

namespace EZ\Currencies\Services;

use EZ\Currencies\Models\Currency;
use Illuminate\Session\SessionManager;
use EZ\Currencies\Contracts\CurrencyRateApi;
use EZ\ModelServices\Traits\ModelServiceGetters;
use EZ\ModelServices\Contracts\ModelServiceContract;
use EZ\Currencies\Exceptions\CurrencyNotFoundException;
use EZ\Currencies\Exceptions\MissingConversionRatesException;
use EZ\Currencies\Exceptions\MissingTargetConversionRateException;

class CurrencyService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    private $api;
    private $session;

    public function __construct(SessionManager $session, CurrencyRateApi $api)
    {
        $this->api = $api;
        $this->session = $session;
        
        $this->model = config("currencies.model");
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findByCode($code)
    {
        return $this->findBy("code", $code);
    }

    /**
     * Find preloaded currency by it's code representation (EUR, USD, etc..)
     * 
     * @param       string              The code of the currency
     * @return      Currency|false      The currency or false if it could not be found
     */
    public function findPreloadedByCode($code)
    {
        return $this->findPreloadedBy("code", $code);
    }

    /**
     * Grab default currency
     * 
     * @return      Currency            The application's default currency
     */
    public function default()
    {
        return $this->findByCode(config("currencies.active_currency.session_key"));
    }

    /**
     * Grab active currency
     * 
     * @return      Currency            The currently active currency
     */
    public function active()
    {
        $code = $this->session->get(config("currencies.active_currency.session_key"));

        if (is_null($code)) return $this->default();

        return $this->findByCode($code);
    }

    /**
     * Set active currency
     * 
     * @param       Currency            The currency we want to set as active currency
     * @return      void
     */
    public function setActive(Currency $currency)
    {
        $this->session->put(config("currencies.active_currency.session_key"), $currency->code);
    }

    /**
     * Set active currency by code
     * 
     * @param       string          The code representing the currency
     * @return      void
     */
    public function setActiveCurrencyByCode($code)
    {
        $this->session->put(config("currencies.active_currency.session_key"), $code);
    }

    /**
     * Update conversion rates (for all currencies)
     * 
     * @return      void
     */
    public function updateConversionRates()
    {
        foreach ($this->getAll() as $currency)
        {
            $this->updateConversionRatesFor($currency);
        }
    }

    public function updateConversionRatesFor(Currency $currency)
    {
        // Grab all available currencies
        $currencies = $this->getAll();

        // Retrieve the conversion rates
        $conversion_rates = $this->api->getConversionRatesFor($currency, $currencies);

        // Save them on the currency
        $currency->conversion_rates = $conversion_rates;
        $currency->save();
    }

    /**
     * Get conversion rates
     * 
     * @return      array               Array containing all currencies & their conversion rates
     */
    public function getConversionRates()
    {
        $out = [];

        foreach ($this->getAll() as $currency)
        {
            $out[$currency->code] = $currency->conversion_rates;
        }

        return collect($out);
    }

    /**
     * Get conversion rates by code
     * 
     * @param       string              The code representing the currency
     * @return      array               Array containing all of the currency's conversion rates
     */
    public function getConversionRatesByCode($code)
    {
        // Attempt to find the currency by it's code
        $currency = $this->findBy("code", $code);
        if (!$currency)
        {
            throw new CurrencyNotFoundException("Could not find a currency with the code '".$code."'.");
        }
        
        // If we found it return the conversion rates
        return $currency->conversion_rates;
    }

    /**
     * Convert given number from one currency to another
     * 
     * @param       Currency            The base currency we'll start from
     * @param       Currency            The target currency we want to convert to
     * @param       mixed               The amount to convert
     * @return      float               The converted amount with 2 decimals
     */
    public function convert(Currency $baseCurrency, Currency $targetCurrency, $x)
    {
        // Make sure the conversion_rates attribute is set on the base currency
        if (is_null($baseCurrency->conversion_rates) || !is_array($baseCurrency->conversion_rates) || count($baseCurrency->conversion_rates) == 0)
        {
            throw new MissingConversionRatesException("Missing conversion rates for currency '".$baseCurrency->code."'.");
        }

        // Make sure there's a conversion rate set for the target currency on the base currency
        if (!array_key_exists($targetCurrency->code, $baseCurrency->conversion_rates))
        {
            throw new MissingTargetConversionRateException("Missing target conversion rate '".$targetCurrency->code."' for currency ".$baseCurrency->code.".");
        }

        // Convert the amount we received using some basic math and the target currency's conversion rate
        $y = $x * $baseCurrency->conversion_rates[$targetCurrency->code];

        // Format and return the output number
        return floatval(number_format($y, 2, ".", ","));
    }

    /**
     * Convert given number from one currency to all other currencies
     * 
     * @param       Currency            The base currency we'll start from
     * @param       mixed               The amount to convert
     * @return      array               Array containing all converted amounts keyed by target currency's code
     */
    public function convertToAll(Currency $baseCurrency, $x)
    {
        $output = [];

        // Make sure the conversion_rates attribute is set on the base currency
        if (is_null($baseCurrency->conversion_rates) || !is_array($baseCurrency->conversion_rates) || count($baseCurrency->conversion_rates) == 0)
        {
            throw new MissingConversionRatesException("Missing conversion rates for currency '".$baseCurrency->code."'.");
        }

        // Process all of the available currencies
        foreach ($this->getAll() as $currency)
        {
            // If it's the base currency; skip it
            if ($currency->id == $baseCurrency->id) continue;

            // Convert the amount to the currency we're processing and add it to the output
            $output[$currency->code] = $this->convert($baseCurrency, $currency, $x);
        }

        // Return the collected converted amounts
        return $output;
    }
}
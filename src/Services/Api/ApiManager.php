<?php

namespace EZ\Currencies\Services\Api;

use Illuminate\Support\Manager;

class ApiManager extends Manager
{
    /**
     * Create an instance of the Fixr.io Currency Rate API driver
     * 
     * @return FixerApi
     */
    protected function createFixerDriver()
    {
        return new FixerApi;
    }

    /**
     * Create an instance of the Ratesapi.io Currency Rate API driver
     * 
     * @return RatesApi
     */
    protected function createRatesDriver()
    {
        return new RatesApi;
    }

    /**
     * Get the default driver
     * 
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app["config"]["currencies.conversion_rates.driver"];
    }
}
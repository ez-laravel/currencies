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
     * Create an instance of the Frankfurter.app Currency Rate API driver
     * 
     * @return FrankfurterApi
     */
    protected function createFrankfurterDriver()
    {
        return new FrankfurterApi;
    }

    /**
     * Create an instance of the exchangeratesapi.io Currency Rate API driver
     * 
     * @return ExchangeratesApi
     */
    protected function createExchangeratesDriver()
    {
        return new ExchangeratesApi;
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
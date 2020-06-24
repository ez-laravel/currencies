<?php

namespace EZ\Currencies\Facades;

use Illuminate\Support\Facades\Facade;

class CurrenciesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "currencies";
    }
}
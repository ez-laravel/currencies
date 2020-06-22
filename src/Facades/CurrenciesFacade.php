<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CurrenciesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "currencies";
    }
}
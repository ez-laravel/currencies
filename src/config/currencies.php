<?php

return [

    // Currency model to use within the service. If create your own extended Currency class set it's namespaced classname here.
    "model" => EZ\Currencies\Models\Currency::class,

    // Active currency configuration
    "active_currency" => [

        // Session key to use to save the active currency on the session
        "session_key" => "active_currency_code",

        // Default currency
        "default_currency" => env("CURRENCIES_DEFAULT", "EUR"),

    ],

    // Conversion rate retrieval configuration
    "conversion_rates" => [

        // What driver should be used? Options are: 'fixer', 
        "driver" => env("CURRENCIES_API_DRIVER", "fixer"),
    
        // API keys
        "api_keys" => [

            // Fixer.io API key
            "fixer" => env("CURRENCIES_FIXER_API_KEY"),

        ],

    ],

];
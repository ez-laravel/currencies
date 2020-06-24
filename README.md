# EZ Laravel Currencies

This package provides your application with the functionality to use currencies and easily retrieve conversion rates and apply them to prices.

It makes use of external API's, some of which require an API key. You'll find more details in the instructions below.

## Installation

Run the following command in your project directory to install the package:
```
composer require ez-laravel/currencies
```

Publish the migrations and seeds using the following command:
```
php artisan vendor:publish --provider="EZ\Currencies\Providers\CurrenciesServiceProvider" --tag=database
```

Run the migrations with:
```
php artisan migrate
```

Update your `DatabaseSeeder.php` class to load the new seeder and afterwards run the following commands to seed your database:
```
composer dumpautoload
php artisan db:seed
```

Run the following command to (optionally) publish the package's config file:
```
php artisan vendor:publish --provider="EZ\Currencies\Providers\CurrenciesServiceProvider" --tag=config
```

## Configuration

#### Default currency

The default currency is set to EUR, which you can change by adding the following key to your `.env` file with the desired currency code:
```
CURRENCIES_DEFAULT=USD
```

#### Currency Conversion Rate API

The following APIs are supported or under development to be supported:

- [x] [Fixer.io](https://fixer.io)
- [x] [Ratesapi.io](https://ratesapi.io/)
- [x] [Frankfurter.app](https://www.frankfurter.app)
- [x] [Exchangeratesapi.io](https://exchangeratesapi.io/)

You can change the drive the package will use by adding the following key to your `.env` file:
```
CURRENCIES_API_DRIVER=fixer
```

#### Fixer

`fixer`

Get an API key from the [fixer.io website](https://fixer.io) and add it to your `.env` file:
```
CURRENCIES_FIXER_API_KEY=xxxxxxxx
```

#### RatesAPI

`rates` 

The [ratesapi.io website](https://ratesapi.io) does not require an API key!

#### Frankfurter

`frankfurter`

The [frankfurter.app website](https://frankfurter.app) does not require an API key!

#### ExchangeRatesAPI

`exchangerates`

The [exchangeratesapi.io website](https://exchangeratesapi.io/) does not require an API key!

## Usage

#### Available methods

```php
// Currency getters
$currencies = Currencies::getAll();
$currency = Currencies::find($id);
$currency = Currencies::findBy($field, $value);
$currency = Currencies::findByCode($code);
$num_currencies = Currencies::countAll();

// Preloaded currency getters
$currencies = Currencies::getAllPreloaded();
$currency = Currencies::findPreloaded($id);
$currency = Currencies::findPreloadedBy($field, $value);

// Currency conversion rate getters
$conversionRates = Currencies::getConversionRates();
$conversionRates = Currencies::getConversionRatesByCode($code);

// Update all currency conversion rates
Currencies::updateConversionRates();

// Conversion methods
$convertedValue = Currencies::convert($fromCurrency, $toCurrency, $x);
$convertedValues = Currencies::convertToAll($fromCurrency, $x);
```

#### Updating conversion rates

When you've just installed the package you should perform the following command to manually update all of your currencies conversion rates:
```
php artisan currencies:update-conversion-rates
```

To keep the conversion rates up-to-date automatically schedule the above command to be ran every day (or at whatever interval you'd like) by updating your `app/Console/Kernel.php` file to include the following:
```php
protected function schedule(Schedule $schedule)
{
    ...
    $schedule->command('currencies:update-conversion-rates')->daily();
}
```
[More information on task scheduling can be found here](https://laravel.com/docs/7.x/scheduling#scheduling-artisan-commands).

#### Extending the Currency model

In most applications you will want to create relationships between the Currency model and for example a Product model or Order model. To do this simply create your own Currency model which extends the `EZ\Currencies\Models\Currency` model and update the model path in the `currencies.php` config file.

So for example:
```php
<?php

namespace App\Models;

use EZ\Currencies\Models\Currency as BaseCurrency;

class Currency extends BaseCurrency
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```
And in `currencies.php`
```php
...
    'model' => App\Models\Currency::class,
...
```

## Contributing

If you'd like to contribute feel free to submit a PR request with your driver(s) or other improvements!
Any other feedback is 
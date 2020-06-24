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
DEFAULT_CURRENCY=USD
```

#### Currency Conversion Rate API


The package supports several APIs to retrieve currency conversion rates. Supported drivers are:

- `fixer` (default)
- `ratesapi`

You can change the drive the package will use by adding the following key to your `.env` file:
```
CURRENCY_API_DRIVER=fixer
```

#### Fixer

Get an API key from the [fixer.io website](https://fixer.io) and add it to your `.env` file:
```
FIXER_API_KEY=xxxxxxxx
```

#### RatesAPI

The [ratesapi.io website](https://ratesapi.io) does not require an API key!

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

## Supported APIs

The following APIs are supported or under development to be supported:

- [x] [Fixer.io](https://fixer.io)
- [ ] [Ratesapi.io](https://ratesapi.io/)
- [ ] [Frankfurter.app](https://www.frankfurter.app)
- [ ] [Exchangeratesapi.io](https://exchangeratesapi.io/)

## Contributing

If you'd like to contribute feel free to submit a PR request with your driver(s) or other improvements!
Any other feedback is 
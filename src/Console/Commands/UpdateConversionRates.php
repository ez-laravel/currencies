<?php

namespace EZ\Currencies\Console\Commands;

use Currencies;
use Illuminate\Console\Command;

class UpdateConversionRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:update-conversion-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates conversion rates for all currencies.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Updating currency conversion rates using the '".config("currencies.conversion_rates.driver")."' driver\n");

        // Grab all currencies we should process
        $currencies = Currencies::getAll();

        // Create a new progress bar 
        $bar = $this->output->createProgressBar(count($currencies));
        $bar->start();

        // Process all currencies
        foreach ($currencies as $currency)
        {
            // Update the currency's conversion rates
            Currencies::updateConversionRatesFor($currency);

            // Advance the bar one step
            $bar->advance();
        }

        // Finish the bar!
        $bar->finish();

        // Display completed message
        $this->info("\n\nCompleted!");
    }
}

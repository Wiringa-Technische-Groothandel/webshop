<?php

namespace WTG\Console\Commands\Import;

use Carbon\Carbon;
use WTG\Models\Product;
use Illuminate\Console\Command;
use WTG\Soap\GetProducts\Response;

/**
 * Import products command.
 *
 * @deprecated
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SoapProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =  'import:soap:products ' .
                            '{--i|index=1 : Start index} ' .
                            '{--c|count=200 : Amount of items to fetch at once} ' .
                            '{--d|dry-run : Do not save the products}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the products via SOAP';

    /**
     * @var int
     */
    protected $index = 1;

    /**
     * @var int
     */
    protected $amount = 200;

    /**
     * @var Carbon
     */
    protected $runTime;

    /**
     * Products constructor.
     *
     * @param  Carbon  $carbon
     */
    public function __construct(Carbon $carbon)
    {
        parent::__construct();

        $this->runTime = $carbon->now();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->output->comment('Importing products...');

        $this->output->progressStart();

        while ($soapProducts = $this->fetchProducts()) {
            foreach ($soapProducts as $soapProduct) {
                $product = Product::createFromSoapProduct($soapProduct);
                $product->setAttribute('synchronized_at', $this->runTime);

                if (! $this->option('dry-run')) {
                    $product->save();
                }

                $this->output->progressAdvance();
            }

            $this->index += $this->amount;

            sleep(1);
        }

        $this->output->progressFinish();

//        $this->output->comment('Removing products...');

//        $this->output->comment('Removed ' . Product::where('synchronized_at', '<', $this->runTime)->delete() . ' products');
    }

    /**
     * Fetch product from a SOAP call.
     *
     * @return Response\Product[]|null
     */
    protected function fetchProducts(): ?array
    {
        /** @var Response $response */
        $response = app('soap')->getAllProducts($this->amount, $this->index);

        if ($response->code === 500) {
            $this->output->error($response->message);

            return null;
        }

        return $response->products;
    }
}
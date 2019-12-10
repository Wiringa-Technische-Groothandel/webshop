<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use Illuminate\Console\Command;

use WTG\Import\ProductImport;

/**
 * Import products command.
 *
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
                            '{--s|startFrom=1 : Start index} ' .
                            '{--c|count=200 : Amount of items to fetch at once} ' .
                            '{--S|skipDownload : Skip downloading the file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the products via SOAP';

    /**
     * @var ProductImport
     */
    protected $importer;

    /**
     * Products constructor.
     *
     * @param ProductImport $importer
     */
    public function __construct(ProductImport $importer)
    {
        parent::__construct();

        $this->importer = $importer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->importer->execute(
            $this->option('count'),
            $this->option('startFrom'),
            $this->option('skipDownload')
        );
    }
}
<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use WTG\Import\Importer\MultiPriceFactorImporter;
use WTG\Import\ImportManager;

/**
 * Import price factors command.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PriceFactors extends AbstractImportCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:price-factors {--S|skipDownload= : Skip file download, use an existing file in storage/app/import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the price factors';

    /**
     * Products constructor.
     *
     * @param ImportManager $importManager
     * @param MultiPriceFactorImporter $importer
     */
    public function __construct(ImportManager $importManager, MultiPriceFactorImporter $importer)
    {
        parent::__construct($importManager, $importer);
    }
}

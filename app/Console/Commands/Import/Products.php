<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use WTG\Import\Importer\MultiProductImporter;
use WTG\Import\ImportManager;

/**
 * Import products command.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Products extends AbstractImportCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products {--S|skipDownload= : Skip file download, use an existing file in storage/app/import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the products';

    /**
     * Products constructor.
     *
     * @param ImportManager $importManager
     * @param MultiProductImporter $importer
     */
    public function __construct(ImportManager $importManager, MultiProductImporter $importer)
    {
        parent::__construct($importManager, $importer);
    }
}

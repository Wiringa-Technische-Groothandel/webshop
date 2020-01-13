<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use WTG\Import\Importer\MultiCategoryImporter;
use WTG\Import\ImportManager;

/**
 * Import categories command.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Categories extends AbstractImportCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:categories {--S|skipDownload= : Skip file download, use an existing file in storage/app/import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the categories';

    /**
     * Products constructor.
     *
     * @param ImportManager $importManager
     * @param MultiCategoryImporter $importer
     */
    public function __construct(ImportManager $importManager, MultiCategoryImporter $importer)
    {
        parent::__construct($importManager, $importer);
    }
}

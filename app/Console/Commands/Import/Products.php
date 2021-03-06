<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use WTG\Import\Importer\MultiProductImporter;
use WTG\Managers\ImportManager;

/**
 * Import products command.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Products extends AbstractImportCommand
{
    /**
     * @var string
     */
    protected $name = 'import:products';

    /**
     * @var string
     */
    protected $description = 'Run a full product import';

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

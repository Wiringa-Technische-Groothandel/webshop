<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use Illuminate\Console\Command;
use Throwable;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\ImportManager;

/**
 * Abstract import command.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class AbstractImportCommand extends Command
{
    /**
     * @var ImportManager
     */
    protected ImportManager $importManager;

    /**
     * @var ImporterInterface
     */
    protected ImporterInterface $importer;

    /**
     * Products constructor.
     *
     * @param ImportManager $importManager
     * @param ImporterInterface $importer
     */
    public function __construct(ImportManager $importManager, ImporterInterface $importer)
    {
        parent::__construct();

        $this->importManager = $importManager;
        $this->importer = $importer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        try {
            if ($this->option('skipDownload')) {
                $this->importer->csvFileName = $this->option('skipDownload');
            }

            if (! $this->importManager->run($this->importer)) {
                throw $this->importManager->getLastError();
            }
        } catch (Throwable $e) {
            $this->getOutput()->error('Import failed: ' . $e->getMessage());

            return;
        }

        $this->getOutput()->success('Import success');
    }
}

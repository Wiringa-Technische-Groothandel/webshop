<?php

namespace WTG\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use WTG\Services\Import\Assortment as Service;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Import from assortment files.
 *
 * @package     WTG\Console
 * @subpackage  Commands\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Assortment extends Command
{
    /**
     * @var string
     */
    protected $signature = 'import:assortment';

    /**
     * @var string
     */
    protected $description = 'Import products by reading files from FTP.';

    /**
     * @var Service
     */
    protected $service;

    /**
     * @var DatabaseManager
     */
    protected $dm;

    /**
     * AssortmentFiles constructor.
     *
     * @param  Service  $service
     * @param  DatabaseManager  $dm
     */
    public function __construct(Service $service, DatabaseManager $dm)
    {
        parent::__construct();

        $this->service = $service;
        $this->dm = $dm;
    }

    /**
     * Run the command.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        $this->dm->transaction(function () {
            $this->runImport();
        });
    }

    /**
     * Run the import.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function runImport()
    {
        $files = $this->service->getFileList();

        $this->output->text('Found '.$files->count().' files.');

        $lastImportedFile = $this->service->getLastImportedFile();
        $newestFile = $this->service->getNewestFile();

        if ($newestFile === $lastImportedFile) {
            $this->output->text('No new files to be imported.');

            return;
        }

        $startFrom = $this->service->getStartFromIndex();

        $this->output->text('Starting from file '.($startFrom));

        $this->output->progressStart($files->count() - $startFrom);

        $count = 0;
        $index = $startFrom;
        while ($files->has($index)) {
            $file = $files->get($index);
            $products = simplexml_load_string(
                $this->service->readFile($file)
            );

            $this->output->progressAdvance();

            $this->service->importProducts($products, $count);

            $index++;
        }

        $this->output->progressFinish();

        $this->service->updateImportData($file);

        $this->output->success('Imported '.$count.' products');
    }
}
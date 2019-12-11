<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use WTG\Services\Import\Assortment as Service;

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
    protected $signature = 'import:assortment {--no-progress : Disable progress display} {--limit=0 : Only read this amount of file groups (0 = unlimited)}';

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
     * @var int
     */
    protected $productCount = 0;

    /**
     * @var bool
     */
    protected $noProgress = false;

    /**
     * @var int
     */
    protected $limit = 0;

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
        $this->noProgress = $this->option('no-progress');
        $this->limit = (int) $this->option('limit');

        $this->dm->transaction(function () {
            $this->runImport();
        });
    }

    /**
     * Run the import.
     *
     * @return void
     */
    protected function runImport()
    {
        $newUploads = $this->service->getNewUploads();

        if ($newUploads->isEmpty()) {
            $this->output->success('No new files to be imported.');

            return;
        }

        $this->output->text(
            sprintf('%d new uploads since %s', $newUploads->count(), $this->service->getLastImportDate()->format('Y-m-d H:i'))
        );

        if ($this->limit > 0) {
            $this->output->text(
                sprintf('Limiting amount of file groups to %d', $this->limit)
            );

            $newUploads = $newUploads->take($this->limit);
        }

        $count = 1;
        $newUploads->each(function (Collection $fileGroup) use (&$count) {
            $this->output->text(sprintf('Processing filegroup %d', $count));

            if (! $this->noProgress) {
                $this->output->progressStart($fileGroup->get('count'));
            }

            $fileGroup->get('files')->each(function ($filename) {
                $xml = simplexml_load_string(
                    $this->service->readFile($filename)
                );

                $this->service->importProducts($xml, $this->productCount);

                if (! $this->noProgress) {
                    $this->output->progressAdvance();
                }
            });

            if (! $this->noProgress) {
                $this->output->progressFinish();
            }

            $count++;
        });

        $this->service->createSeoUrls();
        $this->service->updateImportData();

        $this->output->success(
            sprintf('Processed %d products', $this->productCount)
        );
    }
}
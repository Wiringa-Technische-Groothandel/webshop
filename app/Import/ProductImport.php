<?php

namespace WTG\Import;

use Carbon\Carbon;

use Exception;

use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\FilesystemManager;

use Psr\Log\LoggerInterface;

use Throwable;

use WTG\Import\Downloader\ProductDownloader;
use WTG\Import\Importer\CsvProductImporter;

/**
 * Product import.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductImport
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ProductDownloader
     */
    protected $downloader;

    /**
     * @var Carbon
     */
    protected $carbon;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var CsvProductImporter
     */
    protected $importer;

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
    private $runTime;

    /**
     * Service constructor.
     *
     * @param LoggerInterface $logger
     * @param ProductDownloader $downloader
     * @param CsvProductImporter $importer
     * @param Carbon $carbon
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        LoggerInterface $logger,
        ProductDownloader $downloader,
        CsvProductImporter $importer,
        Carbon $carbon,
        DatabaseManager $databaseManager
    ) {
        $this->logger = $logger;
        $this->downloader = $downloader;
        $this->carbon = $carbon;
        $this->runTime = $carbon->now();
        $this->databaseManager = $databaseManager;
        $this->importer = $importer;
    }

    /**
     * Run a full product import.
     *
     * @param int $amount
     * @param int $startFrom
     * @param bool $skipDownload
     * @throws Throwable
     */
    public function execute(int $amount = 200, int $startFrom = 1, bool $skipDownload = false)
    {
        $this->logger->info('[Product import] Starting product import');

        $this->amount = $amount;
        $this->index = $startFrom;

        $filePath = storage_path('app/import/');
        $filename = sprintf('products-%s.csv', $this->carbon->format('dmYHis'));

        $this->logger->info('[Product import] Creating product CSV ' . $filename);

        try {
            if (! $skipDownload) {
                $this->createCsv($filePath . $filename);
            }
        } catch (Exception $e) {
            $this->logger->error('[Product import] ' . $e->getMessage());
            return;
        }

        $this->logger->info('[Product import] Processing import file');
        $this->processCsv($filePath . $filename);

        $this->logger->info('[Product import] Moving processed file');
        $this->moveProcessedFile($filePath, $filename);

        $this->logger->info('[Product import] Import finished');
    }

    /**
     * Create a product CSV.
     *
     * @param string $filePath
     * @return void
     * @throws Exception
     */
    public function createCsv(string $filePath): void
    {
        $f = fopen($filePath, 'a+');
        $header = [];

        while ($products = $this->downloader->fetchProducts($this->amount, $this->index)) {
            foreach ($products as $product) {
                $attributes = get_object_vars($product);

                if (! $header) {
                    $header = array_keys($attributes);

                    fputcsv($f, $header);
                }

                fputcsv($f, $attributes);
            }

            $this->index += $this->amount;
        }

        fclose($f);
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws Throwable
     */
    public function processCsv(string $filePath): bool
    {
        $success = false;

        try {
            $this->databaseManager->transaction(function () use (&$success, $filePath) {
                $this->importer->execute($filePath);

                $success = true;
            });
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }

        return $success;
    }

    /**
     * Move the processed file.
     *
     * @param string $filePath
     * @param string $filename
     * @return void
     */
    protected function moveProcessedFile(string $filePath, string $filename): void
    {
        // TODO: Fix on the server
        //rename($filePath.$filename, storage_path('app/import/processed/').$filename);
    }
}

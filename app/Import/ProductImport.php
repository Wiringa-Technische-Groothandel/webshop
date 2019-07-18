<?php

namespace WTG\Import;

use Carbon\Carbon;

use Elasticsearch\Client as ElasticsearchClient;
use Exception;

use Illuminate\Database\DatabaseManager;

use Psr\Log\LoggerInterface;

use Throwable;

use WTG\Import\Downloader\ProductDownloader;
use WTG\Import\Importer\CsvProductImporter;
use WTG\Import\Importer\SingleProductImporter;
use WTG\Models\Product;

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
    protected $csvProductImporter;

    /**
     * @var SingleProductImporter
     */
    protected $singleProductImporter;

    /**
     * @var int
     */
    protected $index = 1;

    /**
     * @var int
     */
    protected $amount = 200;

    /**
     * @var ElasticsearchClient
     */
    protected $elastic;

    /**
     * @var Carbon
     */
    private $runTime;

    /**
     * Service constructor.
     *
     * @param LoggerInterface $logger
     * @param ProductDownloader $downloader
     * @param CsvProductImporter $csvProductImporter
     * @param SingleProductImporter $singleProductImporter
     * @param Carbon $carbon
     * @param DatabaseManager $databaseManager
     * @param ElasticsearchClient $elastic
     */
    public function __construct(
        LoggerInterface $logger,
        ProductDownloader $downloader,
        CsvProductImporter $csvProductImporter,
        SingleProductImporter $singleProductImporter,
        Carbon $carbon,
        DatabaseManager $databaseManager,
        ElasticsearchClient $elastic
    ) {
        $this->logger = $logger;
        $this->downloader = $downloader;
        $this->carbon = $carbon;
        $this->runTime = $carbon->now();
        $this->databaseManager = $databaseManager;
        $this->csvProductImporter = $csvProductImporter;
        $this->elastic = $elastic;
        $this->singleProductImporter = $singleProductImporter;
    }

    /**
     * Run a full product import.
     *
     * @param int $amount
     * @param int $startFrom
     * @param bool $skipDownload
     * @return void
     * @throws Throwable
     */
    public function execute(int $amount = 200, int $startFrom = 1, bool $skipDownload = false): void
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

            $this->logger->info('[Product import] Processing import file');
            $this->updateIndex($filePath . $filename);

            $this->logger->info('[Product import] Moving processed file');
            $this->moveProcessedFile($filePath, $filename);
        } catch (Exception $e) {
            $this->logger->error($e);

            return;
        }

        $this->logger->info('[Product import] Import finished');
    }

    /**
     * Update a single product.
     *
     * @param string $sku
     * @return void
     * @throws Exception
     */
    public function executeSingle(string $sku): void
    {
        $product = Product::findBySku($sku, true);
        $soapProduct = $this->downloader->fetchProduct($sku, $product->getSalesUnit());

        $this->singleProductImporter->execute(get_object_vars($soapProduct));
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
                $this->csvProductImporter->execute($filePath);

                $success = true;
            });
        } catch (Throwable | Exception $e) {
            $this->logger->error($e);

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

    /**
     * Create/Update ElasticSearch index.
     *
     * @param string $filePath
     * @return void
     * @throws Throwable
     */
    protected function updateIndex(string $filePath)
    {
        $indexAlias = config('scout.elasticsearch.index');
        $indexClient = $this->elastic->indices();

        try {
            $indices = array_keys($indexClient->get([ 'index' => $indexAlias ]));
            $oldIndex = array_pop($indices);
        } catch (Exception $e) {
            $oldIndex = false;
        }

        $newIndex = $indexAlias . '-' . time();

        $indexClient->create([
            'index' => $newIndex,
            'body' => config('scout.elasticsearch.config')
        ]);

        config([ 'scout.elasticsearch.index' => $newIndex ]);

        $this->processCsv($filePath);

        config([ 'scout.elasticsearch.index' => $indexAlias ]);

        if ($oldIndex) {
            $indexClient->delete([
                'index' => $oldIndex
            ]);
        }

        $indexClient->putAlias([
            'index' => $newIndex,
            'name' => $indexAlias
        ]);
    }
}

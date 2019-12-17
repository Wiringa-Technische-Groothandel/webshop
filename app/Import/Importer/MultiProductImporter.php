<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\Downloader\ProductsDownloader;
use WTG\Import\Parser\CsvWithHeaderParser;
use WTG\Import\Processor\ProductProcessor;
use WTG\Models\Product;

/**
 * Multi product importer.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class MultiProductImporter implements ImporterInterface
{
    /**
     * Full path to the CSV file.
     *
     * @var string
     */
    public string $csvFileName = '';

    /**
     * @var ProductsDownloader
     */
    protected ProductsDownloader $downloader;

    /**
     * @var ProductProcessor
     */
    protected ProductProcessor $processor;

    /**
     * @var CsvWithHeaderParser
     */
    protected CsvWithHeaderParser $parser;

    /**
     * @var ConsoleKernel
     */
    protected ConsoleKernel $console;

    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * MultiProductImporter constructor.
     *
     * @param ProductsDownloader $downloader
     * @param ProductProcessor $processor
     * @param CsvWithHeaderParser $parser
     * @param ConsoleKernel $console
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        ProductsDownloader $downloader,
        ProductProcessor $processor,
        CsvWithHeaderParser $parser,
        ConsoleKernel $console,
        DatabaseManager $databaseManager
    ) {
        $this->downloader = $downloader;
        $this->processor = $processor;
        $this->parser = $parser;
        $this->console = $console;
        $this->databaseManager = $databaseManager;
    }

    /**
     * Run the importer.
     *
     * @return void
     * @throws Exception
     */
    public function import(): void
    {
        if (! $this->csvFileName) {
            $filePath = $this->createCSV();
        } else {
            $filePath = storage_path(
                sprintf('app/import/%s', $this->csvFileName)
            );
        }

        try {
            $this->databaseManager->beginTransaction();

            $this->importCSV($filePath);
            $this->deleteProducts();

            $this->databaseManager->commit();
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }

        $this->updateIndex();
    }

    /**
     * Create a CSV file with product data.
     *
     * @return string CSV file path
     * @throws Exception
     */
    protected function createCSV(): string
    {
        $filePath = storage_path(
            sprintf('app/import/products-%s.csv', Carbon::now()->format('YmdHis'))
        );

        $f = fopen($filePath, 'a+');
        $header = [];

        foreach ($this->downloader->download() as $products) {
            /** @var Collection $products */
            $products->each(
                function ($product) use (&$header, $f) {
                    $allAttributes = get_class_vars(get_class($product));
                    $attributes = array_merge($allAttributes, get_object_vars($product));

                    if (! $header) {
                        $header = array_keys($attributes);

                        fputcsv($f, $header);
                    }

                    fputcsv($f, $attributes);
                }
            );
        }

        fclose($f);

        return $filePath;
    }

    /**
     * Import the CSV.
     *
     * @param string $filePath
     * @return void
     * @throws Exception
     */
    protected function importCSV(string $filePath): void
    {
        $this->parser->setFilePath($filePath);

        foreach ($this->parser->parse() as $line) {
            $this->processor->process($line);
        }
    }

    /**
     * Delete products that have not been updated.
     *
     * @return void
     */
    protected function deleteProducts(): void
    {
        Product::query()->where('synchronized_at', '<', Carbon::createFromTimestamp((int)LARAVEL_START))->delete();
    }

    /**
     * Update the ES index.
     *
     * @return void
     */
    protected function updateIndex(): void
    {
        $this->console->call(
            'index:recreate',
            [
                'index' => config('scout.elasticsearch.index'),
            ]
        );

        $this->console->call(
            'scout:import',
            [
                'model' => Product::class,
            ]
        );
    }
}

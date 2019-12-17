<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use WTG\Catalog\Model\PriceFactor;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\Downloader\PriceFactorDownloader;
use WTG\Import\Parser\CsvWithHeaderParser;
use WTG\Import\Processor\PriceFactorProcessor;

/**
 * Multi price application importer.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class MultiPriceFactorImporter implements ImporterInterface
{
    /**
     * Full path to the CSV file.
     *
     * @var string
     */
    public string $csvFileName = '';

    /**
     * @var PriceFactorDownloader
     */
    protected PriceFactorDownloader $downloader;

    /**
     * @var PriceFactorProcessor
     */
    protected PriceFactorProcessor $processor;

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
     * MultiPriceApplicationImporter constructor.
     *
     * @param PriceFactorDownloader $downloader
     * @param PriceFactorProcessor $processor
     * @param CsvWithHeaderParser $parser
     * @param ConsoleKernel $console
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        PriceFactorDownloader $downloader,
        PriceFactorProcessor $processor,
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
            $this->deleteOldModels();

            $this->databaseManager->commit();
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }
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
            sprintf('app/import/price-factors-%s.csv', Carbon::now()->format('YmdHis'))
        );

        $f = fopen($filePath, 'a+');
        $header = [];

        foreach ($this->downloader->download() as $priceFactors) {
            /** @var Collection $priceFactors */
            $priceFactors->each(
                function ($priceFactor) use (&$header, $f) {
                    $allAttributes = get_class_vars(get_class($priceFactor));
                    $attributes = array_merge($allAttributes, get_object_vars($priceFactor));

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
     * Delete models that have not been updated.
     *
     * @return void
     */
    protected function deleteOldModels(): void
    {
        PriceFactor::query()
            ->where('synchronized_at', '<', Carbon::createFromTimestamp((int)LARAVEL_START))
            ->delete();
    }
}

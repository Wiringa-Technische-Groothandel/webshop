<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\LogManager;
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
class MultiPriceFactorImporter extends MultiImporter implements ImporterInterface
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

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
     * @param LogManager $logManager
     * @param ConsoleKernel $console
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        PriceFactorDownloader $downloader,
        PriceFactorProcessor $processor,
        CsvWithHeaderParser $parser,
        LogManager $logManager,
        ConsoleKernel $console,
        DatabaseManager $databaseManager
    ) {
        parent::__construct($downloader, $processor, $parser);

        $this->logManager = $logManager;
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
            $filePath = storage_path(
                sprintf('app/import/price-factors-%s.csv', Carbon::now()->format('YmdHis'))
            );

            $this->logManager->info('[Price factor importer] Creating CSV');
            $this->createCSV($filePath);
        } else {
            $this->logManager->info(
                sprintf('[Price factor importer] Reading from existing CSV %s', $this->csvFileName)
            );
            $filePath = storage_path(
                sprintf('app/import/%s', $this->csvFileName)
            );
        }

        try {
            $this->databaseManager->beginTransaction();

            $this->logManager->info('[Price factor importer] Importing CSV');
            $this->importCSV($filePath);

            $this->logManager->info('[Price factor importer] Deleting stale products');
            $this->deleteOldModels();

            $this->databaseManager->commit();
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }
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

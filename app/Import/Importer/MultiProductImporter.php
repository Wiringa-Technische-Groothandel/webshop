<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\LogManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\Downloader\ProductsDownloader;
use WTG\Import\Parser\CsvWithHeaderParser;
use WTG\Import\Processor\ProductProcessor;
use WTG\Catalog\Model\Product;

/**
 * Multi product importer.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class MultiProductImporter extends MultiImporter implements ImporterInterface
{
    /**
     * Full path to the CSV file.
     *
     * @var string
     */
    public string $csvFileName = '';

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
     * MultiProductImporter constructor.
     *
     * @param ProductsDownloader $downloader
     * @param ProductProcessor $processor
     * @param CsvWithHeaderParser $parser
     * @param LogManager $logManager
     * @param ConsoleKernel $console
     * @param DatabaseManager $databaseManager
     */
    public function __construct(
        ProductsDownloader $downloader,
        ProductProcessor $processor,
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
     * @throws Throwable
     */
    public function import(): void
    {
        if (! $this->csvFileName) {
            $filePath = storage_path(
                sprintf('app/import/products-%s.csv', Carbon::now()->format('YmdHis'))
            );

            $this->logManager->info('[Product importer] Creating CSV');
            $this->createCSV($filePath);
        } else {
            $this->logManager->info(sprintf('[Product importer] Reading from existing CSV %s', $this->csvFileName));
            $filePath = storage_path(
                sprintf('app/import/%s', $this->csvFileName)
            );
        }

        try {
            $this->databaseManager->beginTransaction();

            $this->logManager->info('[Product importer] Importing CSV');
            $this->importCSV($filePath);

            $this->logManager->info('[Product importer] Deleting stale products');
            $this->deleteProducts();

            $this->databaseManager->commit();
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }

        $this->updateIndex();
    }

    /**
     * Delete products that have not been updated.
     *
     * @return void
     */
    protected function deleteProducts(): void
    {
        Product::withoutSyncingToSearch(
            function () {
                Product::query()
                    ->where('synchronized_at', '<', Carbon::createFromTimestamp((int)LARAVEL_START))
                    ->delete();
            }
        );
    }

    /**
     * Update the ES index.
     *
     * @return void
     */
    protected function updateIndex(): void
    {
        $this->logManager->info('[Product importer] Recreating index');
        $this->console->call(
            'index:recreate',
            [
                'index' => config('scout.elasticsearch.index'),
            ]
        );

        $this->logManager->info('[Product importer] Indexing products');
        $this->console->call(
            'scout:import',
            [
                'model' => Product::class,
            ],
            app()->runningInConsole() ? app(ConsoleOutput::class) : null
        );
    }
}

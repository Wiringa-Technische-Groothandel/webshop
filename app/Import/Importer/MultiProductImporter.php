<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\Downloader\ProductsDownloader;
use WTG\Import\Parser\CsvWithHeaderParser;
use WTG\Import\Processor\ProductProcessor;
use WTG\Catalog\Model\Product;
use WTG\RestClient\Api\RestManagerInterface;
use WTG\RestClient\Model\Rest\GetLastChangeId\Request;
use WTG\RestClient\Model\Rest\GetLastChangeId\Response;

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

    protected LogManager $logManager;
    protected ConsoleKernel $console;
    protected DatabaseManager $databaseManager;
    protected RestManagerInterface $restManager;

    /**
     * MultiProductImporter constructor.
     *
     * @param ProductsDownloader $downloader
     * @param ProductProcessor $processor
     * @param CsvWithHeaderParser $parser
     * @param LogManager $logManager
     * @param ConsoleKernel $console
     * @param DatabaseManager $databaseManager
     * @param RestManagerInterface $restManager
     */
    public function __construct(
        ProductsDownloader $downloader,
        ProductProcessor $processor,
        CsvWithHeaderParser $parser,
        LogManager $logManager,
        ConsoleKernel $console,
        DatabaseManager $databaseManager,
        RestManagerInterface $restManager
    ) {
        parent::__construct($downloader, $processor, $parser);

        $this->logManager = $logManager;
        $this->console = $console;
        $this->databaseManager = $databaseManager;
        $this->restManager = $restManager;
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

        $this->updateProductChangeNumber();
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

    /**
     * Update the db product change number to the latest number.
     *
     * @return void
     */
    protected function updateProductChangeNumber(): void
    {
        /** @var Response $response */
        $response = $this->restManager->handle(new Request());

        DB::table('config')
            ->where('key', 'last_product_change_number')
            ->update(
                ['value' => $response->changeNumberEnd]
            );
    }
}

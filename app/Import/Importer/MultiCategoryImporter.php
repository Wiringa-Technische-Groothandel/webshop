<?php

declare(strict_types=1);

namespace WTG\Import\Importer;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Log\LogManager;
use Throwable;
use WTG\Catalog\Model\Category;
use WTG\Import\Api\ImporterInterface;
use WTG\Import\Downloader\CategoriesDownloader;
use WTG\Import\Parser\CsvWithHeaderParser;
use WTG\Import\Processor\CategoryProcessor;

/**
 * Multi category importer.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class MultiCategoryImporter extends MultiImporter implements ImporterInterface
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * MultiCategoryImporter constructor.
     *
     * @param CategoriesDownloader $downloader
     * @param CategoryProcessor    $processor
     * @param CsvWithHeaderParser  $parser
     * @param LogManager           $logManager
     * @param DatabaseManager      $databaseManager
     */
    public function __construct(
        CategoriesDownloader $downloader,
        CategoryProcessor $processor,
        CsvWithHeaderParser $parser,
        LogManager $logManager,
        DatabaseManager $databaseManager
    ) {
        parent::__construct($downloader, $processor, $parser);

        $this->logManager = $logManager;
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
        if ( ! $this->csvFileName ) {
            $filePath = storage_path(
                sprintf('app/import/categories-%s.csv', Carbon::now()->format('YmdHis'))
            );

            $this->logManager->info('[Category importer] Creating CSV');
            $this->createCSV($filePath);
        } else {
            $this->logManager->info(sprintf('[Category importer] Reading from existing CSV %s', $this->csvFileName));
            $filePath = storage_path(
                sprintf('app/import/%s', $this->csvFileName)
            );
        }

        try {
            $this->databaseManager->beginTransaction();

            $this->logManager->info('[Category importer] Importing CSV');
            $this->importCSV($filePath);

            $this->logManager->info('[Category importer] Building category tree');
            $this->buildTree();

            $this->logManager->info('[Category importer] Deleting stale categories');
            $this->deleteCategories();

            $this->databaseManager->commit();
        } catch (Exception $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }
    }

    /**
     * Delete categories that have not been updated.
     *
     * @return void
     */
    protected function deleteCategories(): void
    {
        Category::query()
            ->where(Category::FIELD_SYNCHRONIZED_AT, '<', Carbon::createFromTimestamp((int) LARAVEL_START))
            ->delete();
    }

    /**
     * Build the category tree.
     *
     * @return void
     */
    protected function buildTree(): void
    {
        Category::unguard();

        // Map all level 1 categories to the root category
        Category::query()->where(Category::FIELD_LEVEL, 1)->update(
            [
                Category::FIELD_PARENT_ID => Category::DEFAULT_ID,
            ]
        );

        // Map the level 2 categories to level 1 parents
        $this->mapToParent(2);
        $this->mapToParent(3);

        Category::reguard();
    }

    /**
     * Map the current level categories to parent level categories.
     *
     * @param int $currentLevel
     * @return void
     */
    protected function mapToParent(int $currentLevel)
    {
        $parentLevel = $currentLevel - 1;

        Category::query()->where(Category::FIELD_LEVEL, $currentLevel)->get()->each(
            function (Category $category) use ($parentLevel) {
                /** @var Category $parent */
                $parent = Category::query()
                    ->where(Category::FIELD_LEVEL, $parentLevel)
                    ->where(Category::FIELD_CODE, substr($category->getCode(), 0, $parentLevel * 2))
                    ->first();

                if ( ! $parent ) {
                    return;
                }

                $category->setParent($parent);
                $category->saveOrFail();
            }
        );
    }
}

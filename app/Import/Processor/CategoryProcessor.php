<?php

declare(strict_types=1);

namespace WTG\Import\Processor;

use Carbon\Carbon;
use Illuminate\Log\LogManager;
use Illuminate\Support\Str;
use Throwable;
use WTG\Catalog\Model\Category;
use WTG\Import\Api\ProcessorInterface;
use WTG\Models\Description;
use WTG\RestClient\Model\Rest\GetProductGroups\Response\Group;
use WTG\RestClient\Model\Rest\ProductResponse;

/**
 * Category data processor.
 *
 * @package     WTG\Import
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CategoryProcessor implements ProcessorInterface
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * AbstractProductProcessor constructor.
     *
     * @param LogManager $logManager
     */
    public function __construct(LogManager $logManager)
    {
        $this->logManager = $logManager;
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function process(array $data): void
    {
        $category = $this->fetchModel($data['erpId']);

        if (! $category) {
            $category = $this->fillModel(new Category(), $data);
        } else {
            $category = $this->fillModel($category, $data);
        }

        $category->saveOrFail();

        $this->logManager->debug('[Category processor] Imported/updated category ' . $data['erpId']);
    }

    /**
     * Find a category for the import process.
     *
     * @param string $erpId
     * @return null|Category
     */
    public function fetchModel(string $erpId): ?Category
    {
        return Category::query()
            ->where(Category::FIELD_ERP_ID, $erpId)
            ->first();
    }

    /**
     * Fill product model.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    protected function fillModel(Category $category, array $data): Category
    {
        $category->setErpId((string)$data['erpId']);
        $category->setName((string)$data['description']);
        $category->setCode((string)$data['code']);
        $category->setLevel((int)$data['level']);
        $category->setShowInMenu(true);
        $category->setSynchronizedAt(Carbon::createFromTimestamp((int)LARAVEL_START));

        return $category;
    }
}

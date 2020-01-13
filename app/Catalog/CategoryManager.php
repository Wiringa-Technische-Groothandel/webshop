<?php

declare(strict_types=1);

namespace WTG\Catalog;

use WTG\Catalog\Api\Model\CategoryInterface;
use WTG\Catalog\Model\Category;

/**
 * Category manager.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CategoryManager
{
    /**
     * Find a category.
     *
     * @param        $value
     * @param string $field
     * @return CategoryInterface
     */
    public function find($value, string $field = CategoryInterface::FIELD_ID): CategoryInterface
    {
        return Category::query()->where($field, $value)->firstOrFail();
    }

    /**
     * Get the root category.
     *
     * @return Category
     */
    public function getRootCategory(): Category
    {
        return Category::query()->find(Category::DEFAULT_ID);
    }

    /**
     * Load the full category tree.
     *
     * @return Category
     */
    public function loadCategoryTree(): Category
    {
        $rootCategory = $this->getRootCategory();
        $maxLevel = Category::query()->max(Category::FIELD_LEVEL);

        $rootCategory->load(rtrim(str_repeat('children.', $maxLevel + 1), '.'));

        return $rootCategory;
    }
}
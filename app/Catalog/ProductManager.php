<?php

declare(strict_types=1);

namespace WTG\Catalog;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\Model\Product;

/**
 * Product manager.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductManager
{
    /**
     * @param string $value
     * @param string $field
     * @param bool $withTrashed
     * @return Product
     * @throws ModelNotFoundException
     */
    public function find(string $value, string $field = ProductInterface::FIELD_SKU, bool $withTrashed = false): Product
    {
        if ($withTrashed) {
            $query = Product::withTrashed();
        } else {
            $query = Product::query();
        }

        /** @var Product $product */
        $product = $query->where($field, $value)->firstOrFail();

        return $product;
    }

    /**
     * @param array $values
     * @param string $field
     * @return Collection
     */
    public function findAll(array $values, string $field = ProductInterface::FIELD_SKU): Collection
    {
        return Product::query()->whereIn($field, $values)->get();
    }

    /**
     * @param ProductInterface $product
     * @return string
     */
    public function getProductCategoryPath(ProductInterface $product): string
    {
        return sprintf(
            '%s  /  %s',
            $product->getSeries(),
            $product->getType()
        );
    }

    /**
     * @param ProductInterface $product
     * @return string
     */
    public function getProductUrl(ProductInterface $product): string
    {
        return route(
            'catalog.product',
            [
                'sku' => $product->getSku(),
            ]
        );
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id)
    {
        Product::query()->where('id', $id)->delete();
    }
}

<?php

declare(strict_types=1);

namespace WTG\Catalog;

use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * @return Product
     * @throws ModelNotFoundException
     */
    public function find(string $value, string $field = ProductInterface::FIELD_SKU): Product
    {
        /** @var Product $product */
        $product = Product::query()->where($field, $value)->firstOrFail();

        return $product;
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
}

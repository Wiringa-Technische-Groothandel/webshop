<?php declare(strict_types=1);

namespace WTG\Repositories;

use WTG\Models\Product;
use WTG\Contracts\Models\ProductContract;

/**
 * Product repository.
 *
 * @package     WTG\Repositories
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductRepository
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * ProductRepository constructor.
     *
     * @param ProductContract $product
     */
    public function __construct(ProductContract $product)
    {
        $this->product = $product;
    }

    /**
     * @param string $sku
     * @return ProductContract|null
     */
    public function findBySku(string $sku): ?ProductContract
    {
        return $this->product->newQuery()->where('sku', $sku)->first();
    }
}
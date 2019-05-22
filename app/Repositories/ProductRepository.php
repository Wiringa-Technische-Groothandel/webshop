<?php declare(strict_types=1);

namespace WTG\Repositories;

use WTG\Models\Product;
use WTG\Contracts\Models\ProductContract;

class ProductRepository
{
    /**
     * @var Product
     */
    protected $product;

    public function __construct(ProductContract $product)
    {
        $this->product = $product;
    }

    public function findBySku(string $sku): ?ProductContract
    {
        return $this->product->newQuery()->where('sku', $sku)->first();
    }
}
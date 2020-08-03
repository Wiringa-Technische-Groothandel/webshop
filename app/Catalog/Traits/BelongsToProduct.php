<?php

declare(strict_types=1);

namespace WTG\Catalog\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WTG\Catalog\Api\ProductInterface;
use WTG\Models\Product;

/**
 * Belongs to product trait.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait BelongsToProduct
{
    /**
     * Product relation.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the related product.
     *
     * @return null|ProductInterface
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->getAttribute('product');
    }

    /**
     * Set the related product.
     *
     * @param ProductInterface $product
     * @return Model
     */
    public function setProduct(ProductInterface $product): Model
    {
        return $this->product()->associate($product);
    }
}

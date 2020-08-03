<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use WTG\Catalog\Api\ProductInterface;
use WTG\Contracts\Models\PackContract;

/**
 * Pack model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Pack extends Model implements PackContract
{
    /**
     * PackProduct relation.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PackProduct::class);
    }

    /**
     * Pack identifier.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the product.
     *
     * @param ProductInterface $product
     * @return PackContract
     */
    public function setProduct(ProductInterface $product): PackContract
    {
        $this->product()->associate($product);

        return $this;
    }

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
     * Get the product.
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        return $this->getAttribute('product');
    }

    /**
     * Get the items.
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->getAttribute('items');
    }
}

<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\PackContract;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\ProductContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Pack model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Pack extends Model implements PackContract
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
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the product.
     *
     * @param  ProductContract  $product
     * @return PackContract
     */
    public function setProduct(ProductContract $product): PackContract
    {
        $this->product()->associate($product);

        return $this;
    }

    /**
     * Get the product.
     *
     * @return ProductContract
     */
    public function getProduct(): ProductContract
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
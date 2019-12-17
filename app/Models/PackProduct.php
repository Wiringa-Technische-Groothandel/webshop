<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WTG\Contracts\Models\PackContract;
use WTG\Contracts\Models\PackProductContract;
use WTG\Contracts\Models\ProductContract;

/**
 * Pack product model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PackProduct extends Model implements PackProductContract
{
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
     * @param ProductContract $product
     * @return PackProductContract
     */
    public function setProduct(ProductContract $product): PackProductContract
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
     * @return ProductContract
     */
    public function getProduct(): ProductContract
    {
        return $this->getAttribute('product');
    }

    /**
     * Set the pack.
     *
     * @param PackContract $pack
     * @return PackProductContract
     */
    public function setPack(PackContract $pack): PackProductContract
    {
        $this->pack()->associate($pack);

        return $this;
    }

    /**
     * Pack relation.
     *
     * @return BelongsTo
     */
    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }

    /**
     * Get the pack.
     *
     * @return PackContract
     */
    public function getPack(): PackContract
    {
        return $this->getAttribute('pack');
    }

    /**
     * Set the amount.
     *
     * @param int $amount
     * @return PackProductContract
     */
    public function setAmount(int $amount): PackProductContract
    {
        return $this->setAttribute('amount', $amount);
    }

    /**
     * Get the amount.
     *
     * @return null|int
     */
    public function getAmount(): ?int
    {
        return $this->getAttribute('amount');
    }
}

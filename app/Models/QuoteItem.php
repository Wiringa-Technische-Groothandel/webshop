<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WTG\Catalog\Model\Product;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\ProductContract;

/**
 * Quote item model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class QuoteItem extends Model implements CartItemContract
{
    /**
     * Get or set the product.
     *
     * @param Product|null $product
     * @return Product|null
     */
    public function setProduct(Product $product = null): ?Product
    {
        if ($product) {
            $this->product()->associate($product);
        }

        return $this->getAttribute('product');
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
     * @return null|Product
     */
    public function getProduct(): ?Product
    {
        return $this->getAttribute('product');
    }

    /**
     * Set the item quantity.
     *
     * @param float|null $quantity
     * @return CartItemContract
     */
    public function setQuantity(float $quantity): CartItemContract
    {
        return $this->setAttribute('qty', $quantity);
    }

    /**
     * Get the item quantity.
     *
     * @return float
     */
    public function getQuantity(): ?float
    {
        return (float)$this->getAttribute('qty');
    }

    /**
     * Set the cart.
     *
     * @param CartContract $cart
     * @return CartItemContract
     */
    public function setCart(CartContract $cart): CartItemContract
    {
        $this->quote()->associate($cart);

        return $this;
    }

    /**
     * Quote relation.
     *
     * @return BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the cart.
     *
     * @return CartItemContract
     */
    public function getCart(): CartItemContract
    {
        return $this->getAttribute('quote');
    }

    /**
     * Set the price.
     *
     * @param float $price
     * @return CartItemContract
     */
    public function setPrice(float $price): CartItemContract
    {
        return $this->setAttribute('price', $price);
    }

    /**
     * Get the price.
     *
     * @return null|float
     */
    public function getPrice(): ?float
    {
        return (float)$this->getAttribute('price');
    }

    /**
     * Set the subtotal.
     *
     * @param float $subtotal
     * @return CartItemContract
     */
    public function setSubtotal(float $subtotal): CartItemContract
    {
        return $this->setAttribute('subtotal', $subtotal);
    }

    /**
     * Get the subtotal.
     *
     * @return null|float
     */
    public function getSubtotal(): ?float
    {
        return (float)$this->getAttribute('subtotal');
    }
}

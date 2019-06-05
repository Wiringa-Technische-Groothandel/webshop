<?php

namespace WTG\Models;

use WTG\Contracts\Models\CartContract;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Quote relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Product relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get or set the product.
     *
     * @param  ProductContract|null  $product
     * @return ProductContract|null
     */
    public function setProduct(ProductContract $product = null): ?ProductContract
    {
        if ($product) {
            $this->product()->associate($product);
        }

        return $this->getAttribute('product');
    }

    /**
     * Get the product.
     *
     * @return null|ProductContract
     */
    public function getProduct(): ?ProductContract
    {
        return $this->getAttribute('product');
    }

    /**
     * Set the item quantity.
     *
     * @param  float|null  $quantity
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
        return $this->getAttribute('qty');
    }

    /**
     * Set the cart.
     *
     * @param  CartContract  $cart
     * @return CartItemContract
     */
    public function setCart(CartContract $cart): CartItemContract
    {
        $this->quote()->associate($cart);

        return $this;
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
     * @param  float  $price
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
        return $this->getAttribute('price');
    }

    /**
     * Set the subtotal.
     *
     * @param  float  $subtotal
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
        return $this->getAttribute('subtotal');
    }
}
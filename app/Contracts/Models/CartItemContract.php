<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use WTG\Models\Product;

/**
 * Cart item contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CartItemContract
{
    /**
     * Set the product.
     *
     * @param Product|null $product
     * @return Product|null
     */
    public function setProduct(Product $product): ?Product;

    /**
     * Get the product.
     *
     * @return null|Product
     */
    public function getProduct(): ?Product;

    /**
     * Set the cart.
     *
     * @param CartContract $cart
     * @return CartItemContract
     */
    public function setCart(CartContract $cart): CartItemContract;

    /**
     * Get the cart.
     *
     * @return CartItemContract
     */
    public function getCart(): CartItemContract;

    /**
     * Set the item quantity.
     *
     * @param float $quantity
     * @return CartItemContract
     */
    public function setQuantity(float $quantity): CartItemContract;

    /**
     * Get the item quantity.
     *
     * @return float
     */
    public function getQuantity(): ?float;

    /**
     * Set the price.
     *
     * @param float $price
     * @return CartItemContract
     */
    public function setPrice(float $price): CartItemContract;

    /**
     * Get the price.
     *
     * @return null|float
     */
    public function getPrice(): ?float;

    /**
     * Set the subtotal.
     *
     * @param float $subtotal
     * @return CartItemContract
     */
    public function setSubtotal(float $subtotal): CartItemContract;

    /**
     * Get the subtotal.
     *
     * @return null|float
     */
    public function getSubtotal(): ?float;
}

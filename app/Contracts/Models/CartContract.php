<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use WTG\Models\Product;

/**
 * Cart contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CartContract
{
    /**
     * Get the product identifier.
     *
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Find or create a cart for a customer.
     *
     * @param CustomerContract $customer
     * @return CartContract
     */
    public function loadForCustomer(CustomerContract $customer): CartContract;

    /**
     * Add a new item to the cart.
     *
     * @param Product $product
     * @param float $quantity
     * @return CartItemContract
     */
    public function addProduct(Product $product, float $quantity): CartItemContract;

    /**
     * Update the item quantity.
     *
     * @param Product $product
     * @param float $quantity
     * @return CartItemContract
     */
    public function updateProduct(Product $product, float $quantity): CartItemContract;

    /**
     * Remove an item from the cart.
     *
     * @param Product $product
     * @return bool
     */
    public function removeProduct(Product $product): bool;

    /**
     * Find a cart item by product.
     *
     * @param Product $product
     * @return null|CartItemContract
     */
    public function findProduct(Product $product): ?CartItemContract;

    /**
     * Check if the cart contains the product.
     *
     * @param Product $product
     * @return bool
     */
    public function hasProduct(Product $product): bool;

    /**
     * Get the delivery address.
     *
     * @return null|AddressContract
     */
    public function getAddress(): ?AddressContract;

    /**
     * Set the delivery address.
     *
     * @param AddressContract $address
     * @return CartContract
     */
    public function setAddress(AddressContract $address): CartContract;

    /**
     * Set the finished at timestamp.
     *
     * @param Carbon $carbon
     * @return CartContract
     */
    public function setFinishedAt(Carbon $carbon): CartContract;

    /**
     * Get the finished at timestamp.
     *
     * @return null|Carbon
     */
    public function getFinishedAt(): ?Carbon;

    /**
     * Get the items currently in the cart.
     *
     * @return Collection
     */
    public function getItems(): Collection;

    /**
     * Amount of items currently in the cart.
     *
     * @return int
     */
    public function getCount(): int;
}

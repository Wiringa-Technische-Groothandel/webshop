<?php

namespace WTG\Contracts\Models;

use Illuminate\Support\Collection;

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
     * @param  CustomerContract  $customer
     * @return CartContract
     */
    public function loadForCustomer(CustomerContract $customer): CartContract;

    /**
     * Add a new item to the cart.
     *
     * @param  ProductContract $product
     * @param  float $quantity
     * @return CartItemContract
     */
    public function addProduct(ProductContract $product, float $quantity): CartItemContract;

    /**
     * Update the item quantity.
     *
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return CartItemContract
     */
    public function updateProduct(ProductContract $product, float $quantity): CartItemContract;

    /**
     * Remove an item from the cart.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function removeProduct(ProductContract $product): bool;

    /**
     * Find a cart item by product.
     *
     * @param  ProductContract  $product
     * @return null|CartItemContract
     */
    public function findProduct(ProductContract $product): ?CartItemContract;

    /**
     * Check if the cart contains the product.
     *
     * @param  ProductContract  $product
     * @return bool
     */
    public function hasProduct(ProductContract $product): bool;

    /**
     * Get the delivery address.
     *
     * @return null|AddressContract
     */
    public function getAddress(): ?AddressContract;

    /**
     * Set the delivery address.
     *
     * @param  AddressContract  $address
     * @return AddressContract
     */
    public function setAddress(AddressContract $address): AddressContract;

    /**
     * Get the items currently in the cart.
     *
     * @return Collection
     */
    public function items(): Collection;

    /**
     * Amount of items currently in the cart.
     *
     * @return int
     */
    public function count(): int;
}
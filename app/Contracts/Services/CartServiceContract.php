<?php

namespace WTG\Contracts\Services;

use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;

/**
 * Interface CartServiceContract
 *
 * @package WTG\Contracts\Services
 */
interface CartServiceContract
{
    /**
     * Add a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProductBySku(CustomerContract $customer, string $sku, float $quantity): ?CartItemContract;

    /**
     * Update a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function updateProductBySku(CustomerContract $customer, string $sku, float $quantity): ?CartItemContract;

    /**
     * Delete a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     */
    public function deleteProductBySku(CustomerContract $customer, string $sku): bool;

    /**
     * Get the cart item count.
     *
     * @param  CustomerContract  $customer
     * @return int
     */
    public function getItemCount(CustomerContract $customer): int;

    /**
     * Get the delivery address of the cart.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDeliveryAddress(CustomerContract $customer): ?AddressContract;

    /**
     * Set the delivery address for the cart.
     *
     * @param  CustomerContract  $customer
     * @param  AddressContract  $address
     * @return bool
     */
    public function setDeliveryAddress(CustomerContract $customer, AddressContract $address): bool;
}
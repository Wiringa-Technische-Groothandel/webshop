<?php

namespace WTG\Contracts\Services;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;

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
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProductBySku(string $sku, float $quantity = 1.0): ?CartItemContract;

    /**
     * Add a product.
     *
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProduct(ProductContract $product, float $quantity = 1.0): ?CartItemContract;

    /**
     * Update a product by sku.
     *
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function updateProductBySku(string $sku, float $quantity): ?CartItemContract;

    /**
     * Delete a product by sku.
     *
     * @param  string  $sku
     * @return bool
     */
    public function deleteProductBySku(string $sku): bool;

    /**
     * Get the cart item count.
     *
     * @return int
     */
    public function getItemCount(): int;

    /**
     * Get the cart items.
     *
     * @param  bool  $withPrices
     * @return Collection
     */
    public function getItems(bool $withPrices = false): Collection;

    /**
     * Get the grand total price.
     *
     * @return float
     */
    public function getGrandTotal(): float;

    /**
     * Get the delivery address of the cart.
     *
     * @return null|AddressContract
     */
    public function getDeliveryAddress(): ?AddressContract;

    /**
     * Set the delivery address for the cart.
     *
     * @param  AddressContract  $address
     * @return bool
     */
    public function setDeliveryAddress(AddressContract $address): bool;
}
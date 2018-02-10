<?php

namespace WTG\Contracts\Services;

use WTG\Contracts\Models\CustomerContract;

/**
 * Favorites service contract.
 *
 * @package     WTG
 * @subpackage  Contracts\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface FavoritesServiceContract
{
    /**
     * Add a list of favorites to the cart.
     *
     * @param  CustomerContract  $customer
     * @param  array  $productIds
     * @return void
     */
    public function addFavoritesToCart(CustomerContract $customer, array $productIds);

    /**
     * Check if a product is marked as favorite.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     */
    public function isFavorite(CustomerContract $customer, string $sku): bool;

    /**
     * Toggle the favorite state of a product.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     */
    public function toggleFavorite(CustomerContract $customer, string $sku): bool;
}
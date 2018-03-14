<?php

namespace WTG\Contracts\Services;

use Illuminate\Support\Collection;

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
     * Get the favorites grouped by the series.
     *
     * @return Collection
     */
    public function getGroupedProducts(): Collection;

    /**
     * Add a list of favorites to the cart.
     *
     * @param  array  $productIds
     * @return void
     */
    public function addFavoritesToCart(array $productIds);

    /**
     * Check if a product is marked as favorite.
     *
     * @param  string  $sku
     * @return bool
     */
    public function isFavorite(string $sku): bool;

    /**
     * Toggle the favorite state of a product.
     *
     * @param  string  $sku
     * @return bool
     */
    public function toggleFavorite(string $sku): bool;
}
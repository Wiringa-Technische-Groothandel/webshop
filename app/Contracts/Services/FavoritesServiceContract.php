<?php

declare(strict_types=1);

namespace WTG\Contracts\Services;

use Illuminate\Support\Collection;
use WTG\Models\Customer;
use WTG\Models\Product;

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
     * @param array $productIds
     * @return void
     */
    public function addFavoritesToCart(array $productIds);

    /**
     * Check if a product is marked as favorite.
     *
     * @param Product $product
     * @param Customer $customer
     * @return bool
     */
    public function isFavorite(Product $product, Customer $customer): bool;

    /**
     * Toggle the favorite state of a product.
     *
     * @param Product $product
     * @param Customer $customer
     * @return bool
     */
    public function toggleFavorite(Product $product, Customer $customer): bool;
}

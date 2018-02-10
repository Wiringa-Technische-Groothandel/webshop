<?php

namespace WTG\Services;

use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Services\FavoritesServiceContract;
use WTG\Exceptions\ProductNotFoundException;

/**
 * Favorites service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FavoritesService implements FavoritesServiceContract
{
    /**
     * Add a list of favorites to the cart.
     *
     * @param  CustomerContract  $customer
     * @param  array  $productIds
     * @return void
     */
    public function addFavoritesToCart(CustomerContract $customer, array $productIds)
    {
        //
    }

    /**
     * Check if a product is marked as favorite.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     * @throws ProductNotFoundException
     */
    public function isFavorite(CustomerContract $customer, string $sku): bool
    {
        $product = app()->make(ProductContract::class)->findBySku($sku);

        if ($product === null) {
            throw new ProductNotFoundException(__('Geen product gevonden voor sku :sku', ['sku' => $sku]));
        }

        return $customer->hasFavorite($product);
    }

    /**
     * Toggle the favorite state of a product.
     *
     * True: Product is marked as favorite
     * False: Product is removed from favorites
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     */
    public function toggleFavorite(CustomerContract $customer, string $sku): bool
    {
        $isFavorite = $this->isFavorite($customer, $sku);
        $product = app()->make(ProductContract::class)->findBySku($sku);

        if ($isFavorite) {
            $customer->removeFavorite($product);
        } else {
            $customer->addFavorite($product);
        }

        return !$isFavorite;
    }
}
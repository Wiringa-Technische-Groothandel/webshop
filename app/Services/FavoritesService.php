<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\ProductContract;
use WTG\Exceptions\ProductNotFoundException;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Contracts\Services\FavoritesServiceContract;

/**
 * Favorites service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FavoritesService implements FavoritesServiceContract
{
    /**
     * @var AuthServiceContract
     */
    protected $authService;

    /**
     * CartService constructor.
     *
     * @param  AuthServiceContract  $authService
     */
    public function __construct(AuthServiceContract $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get the favorites grouped by the series.
     *
     * @return Collection
     */
    public function getGroupedProducts(): Collection
    {
        return $this->authService
            ->getCurrentCustomer()
            ->getFavorites()
            ->mapToGroups(function (ProductContract $item) {
                return [$item->getSeries() => $item];
            });
    }

    /**
     * Add a list of favorites to the cart.
     *
     * @param  array  $productIds
     * @return void
     */
    public function addFavoritesToCart(array $productIds): void
    {
        //
    }

    /**
     * Check if a product is marked as favorite.
     *
     * @param  string  $sku
     * @return bool
     * @throws ProductNotFoundException
     */
    public function isFavorite(string $sku): bool
    {
        $product = app()->make(ProductContract::class)->findBySku($sku);

        if ($product === null) {
            throw new ProductNotFoundException(__('Geen product gevonden voor sku :sku', ['sku' => $sku]));
        }

        return $this->authService->getCurrentCustomer()->hasFavorite($product);
    }

    /**
     * Toggle the favorite state of a product.
     *
     * True: Product is marked as favorite
     * False: Product is removed from favorites
     *
     * @param  string  $sku
     * @return bool
     */
    public function toggleFavorite(string $sku): bool
    {
        $customer = $this->authService->getCurrentCustomer();
        $isFavorite = $this->isFavorite($sku);
        $product = app()->make(ProductContract::class)->findBySku($sku);

        if ($isFavorite) {
            $customer->removeFavorite($product);
        } else {
            $customer->addFavorite($product);
        }

        return !$isFavorite;
    }
}
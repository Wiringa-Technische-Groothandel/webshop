<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use WTG\Catalog\Api\ProductInterface;
use WTG\Contracts\Services\AuthManagerContract;
use WTG\Contracts\Services\FavoritesServiceContract;
use WTG\Exceptions\ProductNotFoundException;
use WTG\Managers\ProductManager;
use WTG\Models\Customer;
use WTG\Models\Product;

/**
 * Favorites service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FavoritesService implements FavoritesServiceContract
{
    protected AuthManagerContract $authService;
    protected ProductManager $productManager;

    /**
     * CartService constructor.
     *
     * @param AuthManagerContract $authService
     * @param ProductManager      $productManager
     */
    public function __construct(AuthManagerContract $authService, ProductManager $productManager)
    {
        $this->authService = $authService;
        $this->productManager = $productManager;
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
            ->mapToGroups(
                function (ProductInterface $item) {
                    return [$item->getSeries() => $item];
                }
            );
    }

    /**
     * Add a list of favorites to the cart.
     *
     * @param array $productIds
     * @return void
     */
    public function addFavoritesToCart(array $productIds): void
    {
        //
    }

    /**
     * Toggle the favorite state of a product.
     *
     * True: Product is marked as favorite
     * False: Product is removed from favorites
     *
     * @param Product $product
     * @param Customer $customer
     * @return bool
     */
    public function toggleFavorite(Product $product, Customer $customer): bool
    {
        $isFavorite = $this->isFavorite($product, $customer);

        if ($isFavorite) {
            $customer->removeFavorite($product);
        } else {
            $customer->addFavorite($product);
        }

        return ! $isFavorite;
    }

    /**
     * Check if a product is marked as favorite.
     *
     * @param Product $product
     * @param Customer $customer
     * @return bool
     */
    public function isFavorite(Product $product, Customer $customer): bool
    {
        return $customer->hasFavorite($product);
    }
}

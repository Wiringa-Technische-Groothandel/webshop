<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Support\Collection;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\ProductManager;
use WTG\Contracts\Services\AuthServiceContract;
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
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;

    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * CartService constructor.
     *
     * @param AuthServiceContract $authService
     * @param ProductManager      $productManager
     */
    public function __construct(AuthServiceContract $authService, ProductManager $productManager)
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
     * @param string $sku
     * @return bool
     */
    public function toggleFavorite(string $sku): bool
    {
        $customer = $this->authService->getCurrentCustomer();
        $isFavorite = $this->isFavorite($sku);
        $product = $this->productManager->find($sku);

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
     * @param string $sku
     * @return bool
     * @throws ProductNotFoundException
     */
    public function isFavorite(string $sku): bool
    {
        $product = $this->productManager->find($sku);

        if ($product === null) {
            throw new ProductNotFoundException(__('Geen product gevonden voor sku :sku', ['sku' => $sku]));
        }

        return $this->authService->getCurrentCustomer()->hasFavorite($product);
    }
}

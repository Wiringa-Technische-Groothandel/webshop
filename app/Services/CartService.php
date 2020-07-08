<?php

declare(strict_types=1);

namespace WTG\Services;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;
use WTG\Catalog\Model\Product;
use WTG\Catalog\PriceManager;
use WTG\Catalog\ProductManager;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Contracts\Services\CartServiceContract;
use WTG\RestClient\Model\Rest\GetProductPrices\Response\Price;

/**
 * Cart service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CartService implements CartServiceContract
{
    /**
     * @var CartContract
     */
    protected CartContract $cart;

    /**
     * @var AuthServiceContract
     */
    protected AuthServiceContract $authService;

    /**
     * @var PriceManager
     */
    protected PriceManager $priceManager;

    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * CartService constructor.
     *
     * @param CartContract $cart
     * @param AuthServiceContract $authService
     * @param PriceManager $priceManager
     * @param ProductManager $productManager
     */
    public function __construct(
        CartContract $cart,
        AuthServiceContract $authService,
        PriceManager $priceManager,
        ProductManager $productManager
    ) {
        $this->cart = $cart;
        $this->authService = $authService;
        $this->priceManager = $priceManager;
        $this->productManager = $productManager;
    }

    /**
     * Add a product by sku.
     *
     * @param string $sku
     * @param float  $quantity
     * @return null|CartItemContract
     */
    public function addProductBySku(string $sku, float $quantity = 1.0): ?CartItemContract
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        return $this->addProduct($product, $quantity);
    }

    /**
     * Find a product.
     *
     * @param string $sku
     * @return null|Product
     */
    protected function findProduct(string $sku): ?Product
    {
        return $this->productManager->find($sku);
    }

    /**
     * Add a product.
     *
     * @param Product $product
     * @param float   $quantity
     * @return null|CartItemContract
     */
    public function addProduct(Product $product, float $quantity = 1.0): ?CartItemContract
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->addProduct($product, $quantity);
    }

    /**
     * Update a product by sku.
     *
     * @param string $sku
     * @param float  $quantity
     * @return null|CartItemContract
     */
    public function updateProductBySku(string $sku, float $quantity): ?CartItemContract
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->updateProduct($product, $quantity);
    }

    /**
     * Delete a product by sku.
     *
     * @param string $sku
     * @return bool
     */
    public function deleteProductBySku(string $sku): bool
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return false;
        }

        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->removeProduct($product);
    }

    /**
     * Destroy the cart.
     *
     * @return bool
     * @throws Exception
     */
    public function destroy(): bool
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->delete();
    }

    /**
     * Get the cart item count.
     *
     * @return int
     */
    public function getItemCount(): int
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->getCount();
    }

    /**
     * Get the cart items.
     *
     * @param bool $withPrices
     * @return Collection
     */
    public function getItems(bool $withPrices = false): Collection
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());
        $items = $this->cart->getItems();

        if (! $withPrices) {
            return $items;
        }

        try {
            $prices = $this->priceManager->fetchCartPrices(
                $this->authService->getCurrentCustomer()->getCompany()->getCustomerNumber(),
                $items
            );
        } catch (Throwable $e) {
            Log::error($e->__toString());
            Log::error('Failed to load prices for quote items: ' . $items->implode('id', ', '));

            // Return the items if the prices failed to load.
            return $items;
        }

        $items->map(
            function (CartItemContract $item) use ($prices) {
                /** @var Price $price */
                $price = $prices->first(
                    function (Price $price) use ($item) {
                        return $price->sku === $item->getProduct()->getSku();
                    }
                );

                if (! $price) {
                    return $item;
                }

                $item->setPrice($price->netPricePerUnit * $price->priceFactor);
                $item->setSubtotal($price->netTotal * $price->priceFactor);
                $item->save();

                return $item;
            }
        );

        return $items;
    }

    /**
     * @return float
     */
    public function getGrandTotal(): float
    {
        return $this->cart->getItems()->sum(
            function (CartItemContract $item) {
                return $item->getPrice() * $item->getQuantity();
            }
        );
    }

    /**
     * Get the delivery address of the cart.
     *
     * @return null|AddressContract
     */
    public function getDeliveryAddress(): ?AddressContract
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->getAddress();
    }

    /**
     * Set the delivery address for the cart.
     *
     * @param AddressContract $address
     * @return bool
     */
    public function setDeliveryAddress(AddressContract $address): bool
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());
        $this->cart->setAddress($address);

        return $this->cart->save();
    }

    /**
     * Mark the cart as finished.
     *
     * @return bool
     */
    public function markFinished(): bool
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());
        $this->cart->setFinishedAt(Carbon::now());

        return $this->cart->save();
    }
}

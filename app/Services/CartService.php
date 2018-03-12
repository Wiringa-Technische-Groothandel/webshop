<?php

namespace WTG\Services;

use WTG\Models\Quote;
use Illuminate\Support\Collection;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Soap\GetProductPricesAndStocks\Response;

/**
 * Cart service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CartService implements CartServiceContract
{
    /**
     * @var Quote
     */
    protected $cart;

    /**
     * @var AuthServiceContract
     */
    protected $authService;

    /**
     * CartService constructor.
     *
     * @param  CartContract  $cart
     * @param  AuthServiceContract  $authService
     */
    public function __construct(CartContract $cart, AuthServiceContract $authService)
    {
        $this->cart = $cart;
        $this->authService = $authService;
    }

    /**
     * Add a product by sku.
     *
     * @param  string  $sku
     * @param  float  $quantity
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
     * Add a product.
     *
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProduct(ProductContract $product, float $quantity = 1.0): ?CartItemContract
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->addProduct($product, $quantity);
    }


    /**
     * Update a product by sku.
     *
     * @param  string  $sku
     * @param  float  $quantity
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
     * @param  string  $sku
     * @return bool
     */
    public function deleteProductBySku(string $sku): bool
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->removeProduct($product);
    }

    /**
     * Get the cart item count.
     *
     * @return int
     */
    public function getItemCount(): int
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());

        return $this->cart->count();
    }

    /**
     * Get the cart items.
     *
     * @param  bool  $withPrices
     * @return Collection
     * @throws \Exception
     */
    public function getItems(bool $withPrices = false): Collection
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());
        $items = $this->cart->items();

        if (! $withPrices) {
            return $items;
        }

        $products = $items->pluck('product');

        /** @var Response $response */
        $response = app('soap')->getProductPricesAndStocks(
            $products,
            $this->authService->getCurrentCustomer()->getCompany()->getCustomerNumber()
        );

        if ($response->code !== 200) {
            \Log::error('Failed to load prices for quote items: ' . $items->implode('id', ', '));

            // Return the items if the prices failed to load.
            return $items;
        }

        $products = collect($response->products);

        $items->map(function (CartItemContract $item) use ($products) {
            $product = $products->first(function ($product) use ($item) {
                return $product->sku === $item->getProduct()->getSku();
            });

            if (! $product) {
                return $item;
            }

            $item->setPrice((float) $product->net_price);
            $item->setSubtotal($product->net_price * $item->getQuantity());
            $item->save();

            return $item;
        });

        return $items;
    }

    /**
     * @return float
     */
    public function getGrandTotal(): float
    {
        return $this->cart->items()->sum(function (CartItemContract $item) {
            return $item->getPrice() * $item->getQuantity();
        });
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
     * @param  AddressContract  $address
     * @return bool
     */
    public function setDeliveryAddress(AddressContract $address): bool
    {
        $this->cart->loadForCustomer($this->authService->getCurrentCustomer());
        $this->cart->setAddress($address);

        return $this->cart->save();
    }

    /**
     * Find a product.
     *
     * @param  string  $sku
     * @return null|ProductContract
     */
    protected function findProduct(string $sku): ?ProductContract
    {
        return app()->make(ProductContract::class)->findBySku($sku);
    }
}
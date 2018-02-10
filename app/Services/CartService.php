<?php

namespace WTG\Services;

use WTG\Models\Quote;
use Illuminate\Support\Collection;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
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
     * CartService constructor.
     *
     * @param  CartContract  $cart
     */
    public function __construct(CartContract $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Add a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProductBySku(CustomerContract $customer, string $sku, float $quantity = 1.0): ?CartItemContract
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        return $this->addProduct($customer, $product, $quantity);
    }

    /**
     * Add a product.
     *
     * @param  CustomerContract  $customer
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function addProduct(CustomerContract $customer, ProductContract $product, float $quantity = 1.0): ?CartItemContract
    {
        $this->cart->loadForCustomer($customer);

        return $this->cart->addProduct($product, $quantity);
    }


    /**
     * Update a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @param  float  $quantity
     * @return null|CartItemContract
     */
    public function updateProductBySku(CustomerContract $customer, string $sku, float $quantity): ?CartItemContract
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        $this->cart->loadForCustomer($customer);

        return $this->cart->updateProduct($product, $quantity);
    }

    /**
     * Delete a product by sku.
     *
     * @param  CustomerContract  $customer
     * @param  string  $sku
     * @return bool
     */
    public function deleteProductBySku(CustomerContract $customer, string $sku): bool
    {
        $product = $this->findProduct($sku);

        if (! $product) {
            return null;
        }

        $this->cart->loadForCustomer($customer);

        return $this->cart->removeProduct($product);
    }

    /**
     * Get the cart item count.
     *
     * @param  CustomerContract  $customer
     * @return int
     */
    public function getItemCount(CustomerContract $customer): int
    {
        $this->cart->loadForCustomer($customer);

        return $this->cart->count();
    }

    /**
     * Get the cart items.
     *
     * @param  CustomerContract  $customer
     * @param  bool  $withPrices
     * @return Collection
     * @throws \Exception
     */
    public function getItems(CustomerContract $customer, bool $withPrices = false): Collection
    {
        $this->cart->loadForCustomer($customer);
        $items = $this->cart->items();

        if (! $withPrices) {
            return $items;
        }

        $products = $items->pluck('product');

        /** @var Response $response */
        $response = app('soap')->getProductPricesAndStocks($products, $customer->getCompany()->getCustomerNumber());

        if ($response->code !== 200) {
            throw new \Exception('Failed to load prices for cart items.');
        }

        $products = collect($response->products);

        $items->map(function (CartItemContract $item) use ($products) {
            $product = $products->first(function ($product) use ($item) {
                return $product->sku === $item->getProduct()->getSku();
            });

            if (! $product) {
                return $item;
            }

            $item->setPrice(format_price($product->net_price));
            $item->setSubtotal(format_price($product->net_price * $item->getQuantity()));

            return $item;
        });

        return $items;
    }

    /**
     * Get the delivery address of the cart.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDeliveryAddress(CustomerContract $customer): ?AddressContract
    {
        $this->cart->loadForCustomer($customer);

        return $this->cart->getAddress();
    }

    /**
     * Set the delivery address for the cart.
     *
     * @param  CustomerContract  $customer
     * @param  AddressContract  $address
     * @return bool
     */
    public function setDeliveryAddress(CustomerContract $customer, AddressContract $address): bool
    {
        $this->cart->loadForCustomer($customer);
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
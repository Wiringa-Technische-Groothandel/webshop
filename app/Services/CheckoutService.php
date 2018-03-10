<?php

namespace WTG\Services;

use WTG\Exceptions\Checkout\EmptyCartException;
use WTG\Models\Order;
use WTG\Models\OrderItem;
use WTG\Contracts\Models\OrderContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\OrderItemContract;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Contracts\Services\CheckoutServiceContract;

/**
 * Checkout service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CheckoutService implements CheckoutServiceContract
{
    /**
     * @var AuthServiceContract
     */
    protected $authService;

    /**
     * @var CartServiceContract
     */
    protected $cartService;

    /**
     * CartService constructor.
     *
     * @param  CartServiceContract  $cartService
     * @param  AuthServiceContract  $authService
     */
    public function __construct(CartServiceContract $cartService, AuthServiceContract $authService)
    {
        $this->cartService = $cartService;
        $this->authService = $authService;
    }

    /**
     * Create an order.
     *
     * @param  null|string  $comment
     * @return OrderContract
     * @throws EmptyCartException
     */
    public function createOrder(?string $comment = null): OrderContract
    {
        $customer = $this->authService->getCurrentCustomer();
        $address = $this->cartService->getDeliveryAddress();
        $items = $this->cartService->getItems(true);

        if (count($items) < 1) {
            throw new EmptyCartException;
        }

        /** @var Order $order */
        $order = app()->make(OrderContract::class);

        $order->setCompany($customer->getCompany());
        $order->setCustomerNumber($customer->getCompany()->getCustomerNumber());
        $order->setName($address->getName());
        $order->setStreet($address->getStreet());
        $order->setPostcode($address->getPostcode());
        $order->setCity($address->getCity());
        $order->setComment($comment);

        $order->save();

        foreach ($items as $item) {
            /** @var CartItemContract $item */
            /** @var OrderItem $orderItem */
            $orderItem = app()->make(OrderItemContract::class);

            $orderItem->setOrder($order);
            $orderItem->setName($item->getProduct()->getName());
            $orderItem->setSku($item->getProduct()->getSku());
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setPrice($item->getPrice());
            $orderItem->setSubtotal($item->getSubtotal());

            $orderItem->save();
        }

        return $order;
    }
}
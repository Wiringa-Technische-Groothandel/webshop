<?php

declare(strict_types=1);

namespace WTG\Contracts\Services;

use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\OrderContract;
use WTG\Contracts\Models\OrderItemContract;

/**
 * Checkout service contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CheckoutServiceContract
{
    /**
     * Convert a quote to an order.
     *
     * @param null|string $comment
     * @return OrderContract
     */
    public function createOrder(?string $comment = null): OrderContract;

    /**
     * Turn a quote item into an order item.
     *
     * @param CartItemContract $item
     * @return OrderItemContract
     */
    public function createOrderItem(CartItemContract $item): OrderItemContract;
}

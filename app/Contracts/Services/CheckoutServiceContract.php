<?php

namespace WTG\Contracts\Services;

use WTG\Contracts\Models\OrderContract;

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
     * @param  null|string  $comment
     * @return OrderContract
     */
    public function createOrder(?string $comment = null): OrderContract;
}
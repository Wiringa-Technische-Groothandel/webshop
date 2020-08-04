<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Parser;

use Carbon\CarbonImmutable;
use WTG\RestClient\Model\Rest\GetOrders\Response\Order;

/**
 * Order parser.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class OrderParser
{
    /**
     * Parse a response order.
     *
     * @param array $item
     * @return Order
     */
    public function parse(array $item): Order
    {
        $order = new Order();
        $order->erpId = $item['id'];
        $order->orderNumber = $item['orderCode'];
        $order->debtorCode = $item['debtorCode'];
        $order->discountPercentage = $item['discountPercentage'];
        $order->netAmount = $item['netAmount'];
        $order->netAmountIncludingVat = $item['netAmountIncludingVat'];
        $order->orderStatus = $item['orderStatus'];
        $order->orderStatusDescription = $item['orderStatusDescription'];
        $order->orderReasonBlocked = $item['orderReasonBlocked'];
        $order->orderDate = CarbonImmutable::parse($item['orderDate']);

        return $order;
    }
}

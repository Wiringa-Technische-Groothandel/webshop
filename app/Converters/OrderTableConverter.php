<?php

namespace WTG\Converters;

use Carbon\Carbon;
use WTG\Checkout\Models\Order;
use WTG\Checkout\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use WTG\Customer\Models\Company;

/**
 * Order table converter.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OrderTableConverter extends AbstractTableConverter implements TableConverter
{
    /**
     * @var array
     */
    protected $csvFields = [
        'id',
        'products',
        'User_id',
        'created_at',
        'updated_at',
        'comment',
        'addressId'
    ];

    /**
     * Create the order with the order_items.
     *
     * @param  array  $data
     * @return Order|null
     */
    public function createModel(array $data): ?Model
    {
        $company = Company::customerNumber($data['User_id'])->first();

        if ($company === null) {
            \Log::warning('[Order table conversion] No company was found for customer number '.$data['User_id']);

            return null;
        }

        $order = new Order;

        $order->setAttribute('company_id', $company->getAttribute('id'));
        $order->setAttribute('comment', $data['comment']);
        $order->setAttribute('created_at', Carbon::parse($data['created_at']));

        $order->save();

        $items = unserialize($data['products']);

        foreach ($items as $item) {
            $orderItem = new OrderItem;

            $orderItem->setAttribute('order_id', $order->getAttribute('id'));
            $orderItem->setAttribute('price', $item['price'] ?? 0.00);
            $orderItem->setAttribute('qty', $item['qty'] ?? 0.00);
            $orderItem->setAttribute('subtotal', $item['subtotal'] ?? 0.00);

            $orderItem->save();
        }
    }
}
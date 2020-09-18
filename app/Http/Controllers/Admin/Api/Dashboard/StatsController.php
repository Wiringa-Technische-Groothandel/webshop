<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Dashboard;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Controller;
use WTG\Models\Customer;
use WTG\Models\Discount;
use WTG\Models\Order;
use WTG\Models\Product;

/**
 * Admin API dashboard stats data controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class StatsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function execute(): Response
    {
        return response()->json(
            [
                'orderCount'    => Order::count(),
                'customerCount' => Customer::count(),
                'productCount'  => Product::count(),
                'discountCount' => Discount::count(),
            ]
        );
    }
}

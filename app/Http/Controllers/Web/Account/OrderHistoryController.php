<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;
use WTG\Http\Controllers\Controller;
use WTG\Models\Customer;

/**
 * Order history controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class OrderHistoryController extends Controller
{
    /**
     * Order history view.
     *
     * @param Request $request
     * @return View
     */
    public function getAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $orders = $customer
            ->company()
            ->with(['orders', 'orders.items'])
            ->first()
            ->getAttribute('orders')
            ->sortByDesc('created_at');

        return view('pages.account.order-history', compact('customer', 'orders'));
    }
}

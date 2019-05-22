<?php

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\DownloadOrderRequest;

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
     * @param  Request  $request
     * @return \Illuminate\View\View
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

    /**
     * Download an order as PDF file.
     *
     * @param  DownloadOrderRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function postAction(DownloadOrderRequest $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $order = $customer
            ->getCompany()
            ->orders()
            ->where('id', $request->input('order'))
            ->first();

        if (! $order) {
            return back()->withErrors(__("Er was geen order gevonden met het opgegeven id."));
        }

        $snappy = app()->make('snappy.pdf');

        return response(
            $snappy->getOutputFromHtml(view('pdf.order', compact('order'))->render()),
            200,
            [
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="order_' . $order->getAttribute('created_at')->format('YmdHi') . '.pdf"'
            ]
        );
    }
}
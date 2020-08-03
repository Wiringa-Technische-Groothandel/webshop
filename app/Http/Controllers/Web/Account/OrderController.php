<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\OrderContract;
use WTG\Contracts\Services\CartManagerContract;
use WTG\Http\Controllers\Controller;
use WTG\Models\Customer;
use WTG\Models\Order;
use WTG\Models\OrderItem;

/**
 * Account order view controller.
 *
 * @package     WTG\Http
 * @copyright   Copyright (c) 2019 Youwe B.V. (https://www.youwe.nl/)
 * @author      Thomas Wiringa  <t.wiringa@youwe.nl>
 */
class OrderController extends Controller
{
    /**
     * @var CartManagerContract
     */
    protected $cartService;

    /**
     * OrderController constructor.
     *
     * @param ViewFactory $view
     * @param CartManagerContract $cartService
     */
    public function __construct(ViewFactory $view, CartManagerContract $cartService)
    {
        parent::__construct($view);

        $this->cartService = $cartService;
    }

    /**
     * @param Request $request
     * @param string $uuid
     * @return View
     */
    public function getAction(Request $request, string $uuid): View
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $order = $this->findOrder($customer, $uuid);

        if (! $order) {
            return back()->withErrors(__('Geen order gevonden met het opgegeven ID'));
        }

        return $this->view->make(
            'pages.account.order-details',
            [
                'order' => $order,
            ]
        );
    }

    /**
     * @param CustomerContract $customer
     * @param string $uuid
     * @return OrderContract
     */
    protected function findOrder(CustomerContract $customer, string $uuid): OrderContract
    {
        /** @var Order $order */
        $order = $customer
            ->getCompany()
            ->orders()
            ->where('uuid', $uuid)
            ->first();

        return $order;
    }

    /**
     * @param Request $request
     * @param string $uuid
     * @return RedirectResponse
     */
    public function postAction(Request $request, string $uuid): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $order = $this->findOrder($customer, $uuid);

        if (! $order) {
            return back()->withErrors(__('Geen order gevonden met het opgegeven ID'));
        }

        $order->getItems()->each(
            function (OrderItem $item) {
                $product = $item->getProduct();

                if (! $product) {
                    return;
                }

                $this->cartService->addProduct($product, $item->getQuantity());
            }
        );

        return back()->with('status', __('De producten zijn toegevoegd aan de winkelwagen'));
    }
}

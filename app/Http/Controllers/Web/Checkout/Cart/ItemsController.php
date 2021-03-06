<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Checkout\Cart;

use Illuminate\Http\JsonResponse;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CartManagerContract;
use WTG\Http\Controllers\Controller;

/**
 * Checkout cart items controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout\Cart
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ItemsController extends Controller
{
    /**
     * @var CartManagerContract
     */
    protected CartManagerContract $cartService;

    /**
     * CartController constructor.
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
     * Get the items in the cart.
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        $items = $this->cartService->getItems(true);

        return response()->json(
            [
                'payload' => [
                    'items'      => $items,
                    'grandTotal' => format_price($this->cartService->getGrandTotal()),
                ],
            ]
        );
    }
}

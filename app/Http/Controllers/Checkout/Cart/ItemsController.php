<?php

namespace WTG\Http\Controllers\Checkout\Cart;

use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use WTG\Contracts\Models\CartItemContract;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CartServiceContract;

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
     * @var CartServiceContract
     */
    protected $cartService;

    /**
     * CartController constructor.
     *
     * @param  ViewFactory  $view
     * @param  CartServiceContract  $cartService
     */
    public function __construct(ViewFactory $view, CartServiceContract $cartService)
    {
        parent::__construct($view);

        $this->cartService = $cartService;
    }

    /**
     * Get the items in the cart.
     *
     * @param  Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getAction(Request $request)
    {
        $items = $this->cartService->getItems(true);

        return response()->json([
            'payload' => [
                'items' => $items,
                'grandTotal' => format_price($this->cartService->getGrandTotal())
            ]
        ]);
    }
}
<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Checkout;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\Checkout\Cart\AddProductRequest;
use WTG\Http\Requests\Checkout\Cart\UpdateRequest;

/**
 * Cart controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CartController extends Controller
{
    /**
     * @var CartServiceContract
     */
    protected $cartService;

    /**
     * CartController constructor.
     *
     * @param ViewFactory $view
     * @param CartServiceContract $cartService
     */
    public function __construct(ViewFactory $view, CartServiceContract $cartService)
    {
        parent::__construct($view);

        $this->cartService = $cartService;
    }

    /**
     * Cart overview page.
     *
     * @return View
     */
    public function getAction()
    {
        $previousUrl = url()->previous();

        if (! preg_match('/.*checkout/', $previousUrl)) {
            session()->flash('continue.url', $previousUrl);
        }

        return view('pages.checkout.cart');
    }

    /**
     * Add a product to the cart.
     *
     * @param AddProductRequest $request
     * @return JsonResponse
     */
    public function putAction(AddProductRequest $request): JsonResponse
    {
        $cartItem = $this->cartService->addProductBySku(
            $request->input('product'),
            (float)$request->input('quantity')
        );

        if (! $cartItem) {
            return $this->productNotFoundResponse($request->input('product'));
        }

        return response()->json(
            [
                'message' => __(
                    "Toegevoegd aan uw winkelwagen: <br><br> :quantity x :product",
                    [
                        'quantity' => $request->input('quantity'),
                        'product'  => $cartItem->getProduct()->getName(),
                    ]
                ),
                'success' => true,
                'count'   => $this->cartService->getItemCount(),
                'code'    => 200,
            ]
        );
    }

    /**
     * Send a product not found response.
     *
     * @param string $sku
     * @return JsonResponse
     */
    protected function productNotFoundResponse(string $sku): JsonResponse
    {
        return response()->json(
            [
                'message' => __('Geen product gevonden met sku :sku', ['sku' => $sku]),
                'success' => false,
                'code'    => 400,
            ]
        );
    }

    /**
     * Update the cart.
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function patchAction(UpdateRequest $request): JsonResponse
    {
        $cartItem = $this->cartService->updateProductBySku(
            (string)$request->input('sku'),
            (float)$request->input('quantity')
        );

        if (! $cartItem) {
            return $this->productNotFoundResponse($request->input('sku'));
        }

        return response()->json(
            [
                'success' => true,
                'code'    => 200,
            ]
        );
    }

    /**
     * Remove an item from the cart.
     *
     * @param null|string $sku
     * @return JsonResponse
     */
    public function deleteAction(?string $sku = null): JsonResponse
    {
        try {
            if ($sku) {
                $this->cartService->deleteProductBySku($sku);
            } else {
                $this->cartService->destroy();
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'success' => false,
                    'code'    => 400,
                ]
            );
        }

        return response()->json(
            [
                'message' => $sku ?
                    __('Het product is verwijderd uit uw winkelwagen.') :
                    __('De producten zijn verwijderd uit uw winkelwagen.'),
                'count'   => $this->cartService->getItemCount(),
                'success' => true,
                'code'    => 200,
            ]
        );
    }
}

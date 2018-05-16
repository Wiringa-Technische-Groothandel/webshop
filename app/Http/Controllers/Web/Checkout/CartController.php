<?php

namespace WTG\Http\Controllers\Web\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use WTG\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Http\Requests\Checkout\Cart\UpdateRequest;
use WTG\Http\Requests\Checkout\Cart\AddProductRequest;

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
     * @param  ViewFactory  $view
     * @param  CartServiceContract  $cartService
     */
    public function __construct(ViewFactory $view, CartServiceContract $cartService)
    {
        parent::__construct($view);

        $this->cartService = $cartService;
    }

    /**
     * Cart overview page.
     *
     * @return \Illuminate\View\View
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
     * @param  AddProductRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function putAction(AddProductRequest $request): JsonResponse
    {
        $cartItem = $this->cartService->addProductBySku(
            $request->input('product'),
            $request->input('quantity')
        );

        if (! $cartItem) {
            return $this->productNotFoundResponse($request->input('product'));
        }

        return response()->json([
            'message' => __('Het product is toegevoegd aan uw winkelwagen.'),
            'success' => true,
            'count' => $this->cartService->getItemCount(),
            'code' => 200
        ]);
    }

    /**
     * Update the cart.
     *
     * @param  UpdateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patchAction(UpdateRequest $request)
    {
        $cartItem = $this->cartService->updateProductBySku(
            $request->input('sku'),
            $request->input('quantity')
        );

        if (! $cartItem) {
            return $this->productNotFoundResponse($request->input('sku'));
        }

        return response()->json([
            'success' => true,
            'code' => 200
        ]);
    }

    /**
     * Remove an item from the cart.
     *
     * @param  null|string  $sku
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(?string $sku = null)
    {
        try {
            if ($sku) {
                $this->cartService->deleteProductBySku($sku);
            } else {
                $this->cartService->destroy();
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false,
                'code' => 400
            ]);
        }

        return response()->json([
            'message' => $sku ?
                __('Het product is verwijderd uit uw winkelwagen.') :
                __('De producten zijn verwijderd uit uw winkelwagen.'),
            'count' => $this->cartService->getItemCount(),
            'success' => true,
            'code' => 200
        ]);
    }

    /**
     * Send a product not found response.
     *
     * @param  string  $sku
     * @return \Illuminate\Http\JsonResponse
     */
    protected function productNotFoundResponse(string $sku): JsonResponse
    {
        return response()->json([
            'message' => __('Geen product gevonden met sku :sku', ['sku' => $sku]),
            'success' => false,
            'code' => 400
        ]);
    }
}
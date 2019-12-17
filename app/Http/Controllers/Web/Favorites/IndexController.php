<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Favorites;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\FavoritesServiceContract;
use WTG\Exceptions\ProductNotFoundException;
use WTG\Http\Controllers\Controller;
use WTG\Models\Product;

/**
 * Favorites index controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Favorites
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var FavoritesServiceContract
     */
    protected $favoritesService;

    /**
     * FavoritesController constructor.
     *
     * @param ViewFactory $view
     * @param FavoritesServiceContract $favoritesService
     */
    public function __construct(
        ViewFactory $view,
        FavoritesServiceContract $favoritesService
    ) {
        parent::__construct($view);

        $this->favoritesService = $favoritesService;
    }

    /**
     * Check if a product is in the favorites.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $sku = $request->input('sku');

        try {
            $isFavorite = $this->favoritesService->isFavorite($sku);
        } catch (ProductNotFoundException $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'success' => false,
                    'code'    => 400,
                ],
                400
            );
        }

        return response()->json(
            [
                'isFavorite' => $isFavorite,
                'buttonText' => $isFavorite ? __('Verwijderen') : __('Toevoegen'),
                'success'    => true,
                'code'       => 200,
            ]
        );
    }

    /**
     * Add or remove a product from the favorites.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function patchAction(Request $request): JsonResponse
    {
        $sku = $request->input('sku');

        try {
            $added = $this->favoritesService->toggleFavorite($sku);
        } catch (ProductNotFoundException $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'success' => false,
                    'code'    => 400,
                ],
                400
            );
        }

        return response()->json(
            [
                'added'            => $added,
                'buttonText'       => $added ? __('Verwijderen') : __('Toevoegen'),
                'notificationText' => $added ?
                    __('Het product is toegevoegd aan uw favorieten.') :
                    __('Het product is verwijderd uit uw favorieten.'),
                'success'          => true,
                'code'             => 200,
            ]
        );
    }

    /**
     * Remove a product from the favorites.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request): JsonResponse
    {
        $sku = $request->input('sku');

        /** @var CustomerContract $customer */
        $customer = $request->user();
        $product = Product::findBySku($sku);

        if (! $product) {
            return response()->json(
                [
                    'message' => __('Geen product gevonden voor sku :sku', ['sku' => $sku]),
                ],
                400
            );
        }

        $customer->removeFavorite($product);

        return response()->json(
            [
                'message' => __('Het product is verwijderd uit uw favorieten.'),
                'success' => true,
                'code'    => 200,
            ]
        );
    }
}

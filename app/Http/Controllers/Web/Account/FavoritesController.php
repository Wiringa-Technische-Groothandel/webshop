<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account;

use Illuminate\Http\JsonResponse;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Contracts\Services\FavoritesServiceContract;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\AddFavoritesToCartRequest;
use WTG\Models\Product;

/**
 * Favorites controller.
 *
 * @package     WTG\Favorites
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FavoritesController extends Controller
{
    /**
     * @var CartServiceContract
     */
    protected $cartService;

    /**
     * @var FavoritesServiceContract
     */
    protected $favoritesService;

    /**
     * FavoritesController constructor.
     *
     * @param ViewFactory $view
     * @param CartServiceContract $cartService
     * @param FavoritesServiceContract $favoritesService
     */
    public function __construct(
        ViewFactory $view,
        CartServiceContract $cartService,
        FavoritesServiceContract $favoritesService
    ) {
        parent::__construct($view);

        $this->cartService = $cartService;
        $this->favoritesService = $favoritesService;
    }

    /**
     * List of favorites
     *
     * @return View
     */
    public function getAction()
    {
        $favorites = $this->favoritesService->getGroupedProducts();

        return view('pages.account.favorites', compact('favorites'));
    }

    /**
     * Put favorites in the cart.
     *
     * @param AddFavoritesToCartRequest $request
     * @return JsonResponse
     */
    public function putAction(AddFavoritesToCartRequest $request)
    {
        $errors = [];

        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);

            if ($product === null) {
                $errors[] = __(sprintf("Geen product gevonden met id %s", $productId));

                continue;
            }

            $this->cartService->addProduct($product);
        }

        return response()->json(
            [
                'message' => __("De producten zijn toegevoegd aan uw winkelwagen."),
                'errors'  => $errors,
                'cartQty' => $this->cartService->getItemCount(),
            ]
        );
    }
}

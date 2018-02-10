<?php

namespace WTG\Http\Controllers\Account;

use WTG\Models\Product;
use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Http\Requests\AddFavoritesToCartRequest;
use WTG\Contracts\Services\FavoritesServiceContract;

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
     * @param  ViewFactory  $view
     * @param  CartServiceContract  $cartService
     * @param  FavoritesServiceContract  $favoritesService
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
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $favorites = $customer->getFavorites()->sortBy('name');

        return view('pages.account.favorites', compact('favorites'));
    }

    /**
     * Put favorites in the cart.
     *
     * @param AddFavoritesToCartRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function putAction(AddFavoritesToCartRequest $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $errors = [];

        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);

            if ($product === null) {
                $errors[] = __(sprintf("Geen product gevonden met id %s", $productId));

                continue;
            }

            $this->cartService->addProduct($customer, $product);
        }

        return response()->json([
            'message' => __("De producten zijn toegevoegd aan uw winkelwagen."),
            'errors'  => $errors,
            'cartQty' => $this->cartService->getItemCount($customer)
        ]);
    }

    /**
     * Check if a product is in the users favorites
     *
     * @param  CheckFavoriteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function check(CheckFavoriteRequest $request)
    {
        $productId = $request->input('product');

        /** @var FavoriteInterface $favorite */
        $favorite = app()->make(FavoriteInterface::class)
            ->customer(Auth::id())
            ->get()
            ->first(function ($favorite) use ($productId) {
                /** @var FavoriteInterface $favorite */
                return $favorite->getProductId() === $productId;
            });

        if ($favorite === null) {
            return response([
                'success' => true,
                'toggle_url' => route('customer::account.favorites::add', ['product' => $productId]),
                'text' => trans('customer::button.add')
            ]);
        }

        return response([
            'success' => true,
            'toggle_url' => route('customer::account.favorites::delete', ['favorite' => $favorite->getId()]),
            'text' => trans('customer::button.delete')
        ]);
    }

    /**
     * Add a product to the favorites
     *
     * @param  AddFavoriteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function add(AddFavoriteRequest $request)
    {
        $productId = $request->input('product');

        /** @var FavoriteInterface $favorite */
        $favorite = app()->make(FavoriteInterface::class)
            ->add(
                Auth::id(),
                $productId
            );

        if ($favorite) {
            return response([
                'success' => true,
                'message' => trans('customer::favorite.added'),
                'toggle_url' => route('customer::account.favorites::delete', ['favorite' => $favorite->getId()]),
                'text' => trans('customer::button.delete')
            ]);
        }

        return response([
            'success' => false,
            'message' => trans('customer::favorite.already_added')
        ], 400);
    }

    /**
     * Remove a product from the users favorites
     *
     * @param  DeleteFavoriteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteFavoriteRequest $request)
    {
        $favoriteId = $request->input('favorite');

        /** @var FavoriteInterface $favorite */
        $favorite = app()->make(FavoriteInterface::class)
            ->customer(Auth::id())
            ->find($favoriteId);

        if ($favorite === null) {
            return response([
                'success' => false,
                'message' => trans('customer::favorite.not_found'),
                'toggle_url' => route('customer::account.favorites::delete', ['favorite' => $favoriteId]),
                'text' => trans('customer::favorite.delete'),
            ]);
        }

        $productId = $favorite->getProductId();
        $favorite->delete();

        return response([
            'success' => true,
            'message' => trans('customer::favorite.deleted'),
            'toggle_url' => route('customer::account.favorites::add', ['product' => $productId]),
            'text' => trans('customer::button.add')
        ]);
    }
}
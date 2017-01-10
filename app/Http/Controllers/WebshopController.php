<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Cart;
use Input;
use Helper;
use Request;
use Session;
use Redirect;
use App\Order;
use App\Product;

/**
 * Class WebshopController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class WebshopController extends Controller
{
    /**
     * The main webshop page.
     *
     * @return \Illuminate\View\View
     */
    public function main()
    {
        $brands = DB::table('products')->select('brand')->distinct()->get();
        $series = DB::table('products')->select('series')->distinct()->get();
        $types = DB::table('products')->select('type')->distinct()->get();

        // Sort the object arrays
        usort($brands, function ($a, $b) {
            return strcmp(strtolower($a->brand), strtolower($b->brand));
        });

        usort($series, function ($a, $b) {
            return strcmp(strtolower($a->series), strtolower($b->series));
        });

        usort($types, function ($a, $b) {
            return strcmp(strtolower($a->type), strtolower($b->type));
        });

        return view('webshop.main', [
            'brands' => array_pluck($brands, 'brand'),
            'series' => array_pluck($series, 'series'),
            'types'  => array_pluck($types, 'type'),
        ]);
    }

    /**
     * The product page
     * Will throw 404 error when no product matches the product id.
     *
     * @param  bool  $product_Id
     * @return \Illuminate\View\View
     */
    public function showProduct($product_Id = false)
    {
        if ($product_Id === false) {
            abort(404);
        }

        Session::flash('product_id', $product_Id);

        $product = Product::where('number', $product_Id)->firstOrFail();
        $discount = (Auth::check() ? Helper::getProductDiscount(Auth::user()->company_id, $product->group, $product->number) : null);
        $prevPage = Input::get('ref');

        if ($product->related_products) {
            foreach (explode(',', $product->related_products) as $related_product) {
                $related_products[] = Product::where('number', $related_product)->first();
            }
        } else {
            $related_products = null;
        }

        if (preg_match('/(search|clearance|specials)/', Request::server('HTTP_REFERER'))) {
            Session::put('continueShopping', Request::server('HTTP_REFERER'));
        }

        return view('webshop.product', [
                'productData'      => $product,
                'discount'         => $discount,
                'related_products' => $related_products,
                'prevPage'         => $prevPage,
            ]
        );
    }

    /**
     * Add the products from a previous order to the cart.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reorder($orderId)
    {
        $order = Order::where('User_id', Auth::user()->company_id)
            ->where('id', $orderId)
            ->firstOrFail();

        $order = unserialize($order->products);
        $errors = false;

        foreach ($order as $item) {
            if (Product::where('number', $item['id'])->count()) {
                $product = Product::where('number', $item['id'])->firstOrFail();
                $cartData = [
                    'id'      => $product->number,
                    'name'    => $product->name,
                    'qty'     => $item['qty'],
                    'price'   => $product->real_price,
                    'options' => [
                        'special' => false,
                        'korting' => Helper::getProductDiscount(Auth::user()->login, $product->group, $product->number),
                    ],
                ];

                // Add the product to the cart
                Cart::add($cartData);
            } else {
                $errors = true;
            }
        }

        if ($errors) {
            return redirect('cart')
                ->withErrors('Sommige producten konden niet toegevoegd worden aan uw winkelmandje');
        } else {
            return redirect('cart')
                ->with('status', 'De order producten zijn in uw winkelmandje geplaatst.');
        }
    }

    /**
     * Show the order finished screen.
     *
     * @return \Illuminate\View\View|Redirect
     */
    public function orderFinished()
    {
        if (Session::pull('order')) {
            return view('webshop.finishedOrder');
        } else {
            return redirect('/');
        }
    }
}

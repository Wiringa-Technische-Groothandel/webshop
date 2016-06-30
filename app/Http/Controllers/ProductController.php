<?php namespace App\Http\Controllers;

use App\Exceptions\ProductNotFoundException;
use App\PackProduct;
use App\Pack;
use Illuminate\Http\Request;
use App\Product;
use Session, Auth, Helper;

class ProductController extends Controller {

    /**
     * The product page
     * Will throw 404 error when no product matches the product id
     *
     * @param Request $request
     * @param int $product_Id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProduct(Request $request, $product_Id = null)
    {
        // Store the product id in the session
        Session::flash('product_id', $product_Id);

        try {
            $product  = Product::where('number', $product_Id)->firstOrFail();
        } catch (\Exception $e) {
            throw new ProductNotFoundException($product_Id);
        }

        $discount = (Auth::check() ? Helper::getProductDiscount(Auth::user()->login, $product->group, $product->number) : null);
        $prevPage = $request->get('ref');
        $related_products = [];
        $pack_list = [];

        if ($product->related_products) {
            foreach (explode(',', $product->related_products) as $related_product) {
                $related_products[] = Product::where('number', $related_product)->first();
            }
        } else {
            $related_products = false;
        }

        if ($pack = PackProduct::select(['pack_id'])->where('product', $product_Id)->get()->toArray()) {
            $pack_list = Pack::whereIn('id', $pack)->get();
        }

        if (preg_match("/(search|clearance|specials)/", $request->server('HTTP_REFERER'))) {
            Session::put('continueShopping', $request->server('HTTP_REFERER'));
        }

        return view('webshop.product', [
            'product'           => $product,
            'discount'          => $discount,
            'related_products'  => $related_products,
            'prevPage'          => $prevPage,
            'pack_list'         => $pack_list
        ]);
    }

}
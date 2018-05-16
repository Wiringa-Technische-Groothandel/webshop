<?php

namespace WTG\Http\Controllers\Web\Catalog;

use WTG\Models\Product;
use Illuminate\View\View;
use WTG\Http\Controllers\Controller;

/**
 * Product controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductController extends Controller
{
    /**
     * Product detail page.
     *
     * @param  string  $sku
     * @return \Illuminate\View\View
     */
    public function getAction(string $sku): View
    {
        $product = Product::findBySku($sku);

        if (! $product) {
            abort(404, __("Er is geen product gevonden met productnummer :sku", ['sku' => $sku]));
        }

        return view('pages.catalog.product', compact('product'));
    }
}
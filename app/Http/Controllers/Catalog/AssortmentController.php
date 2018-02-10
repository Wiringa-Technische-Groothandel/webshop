<?php

namespace WTG\Http\Controllers\Catalog;

use WTG\Models\Product;
use WTG\Http\Controllers\Controller;

/**
 * Assortment controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AssortmentController extends Controller
{
    /**
     * Assortment page.
     *
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        $results = collect([
            'products' => Product::paginate(10),
            'brands' => Product::orderBy('brand')->distinct()->pluck('brand'),
            'series' => Product::orderBy('series')->distinct()->pluck('series'),
            'types' => Product::orderBy('type')->distinct()->pluck('type'),
        ]);

        return view('pages.catalog.assortment', compact('results'));
    }
}
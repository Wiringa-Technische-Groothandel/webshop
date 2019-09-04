<?php declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Catalog;

use Illuminate\Contracts\View\View;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Product;

/**
 * Product catalog overview.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * Product overview.
     *
     * @return View
     */
    public function getAction(): View
    {
        $products = Product::all(['sku', 'group', 'name', 'created_at', 'updated_at']);

        return $this->view->make('pages.admin.catalog', [
            'products' => $products
        ]);
    }
}
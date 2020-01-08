<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Catalog;

use Symfony\Component\HttpFoundation\Response;
use WTG\Catalog\Model\Product;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Admin API product catalog index controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @return Response
     */
    public function execute(): Response
    {
        $products = Product::all([ 'sku', 'group', 'name', 'created_at', 'updated_at' ]);

        return response()->json(
            [
                'products' => $products,
            ]
        );
    }
}

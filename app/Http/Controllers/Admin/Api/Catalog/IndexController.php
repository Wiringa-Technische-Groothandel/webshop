<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Catalog;

use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Product;

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
        $products = Product::query()
            ->get(['sku', 'group', 'name', 'created_at', 'updated_at']);

        return response()->json(
            [
                'products' => $products,
            ]
        );
    }
}

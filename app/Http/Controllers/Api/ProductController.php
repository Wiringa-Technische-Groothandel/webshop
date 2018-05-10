<?php

namespace WTG\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use WTG\Http\Controllers\Controller;
use WTG\Contracts\Models\ProductContract;

/**
 * Product controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Api
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductController extends Controller
{
    /**
     * Fetch a product.
     *
     * @param  string  $sku
     * @return JsonResponse
     */
    public function getAction(string $sku): JsonResponse
    {
        $product = app()->make(ProductContract::class)->where('sku', $sku)->firstOrFail();

        return response()->json([
            'product' => $product
        ]);
    }
}
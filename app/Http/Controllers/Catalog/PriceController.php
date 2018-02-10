<?php

namespace WTG\Http\Controllers\Catalog;

use WTG\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\FetchPriceRequest;
use WTG\Contracts\Models\CustomerContract;
use Illuminate\Database\Eloquent\Collection;
use WTG\Soap\GetProductPricesAndStocks\Response;

/**
 * Assortment controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PriceController extends Controller
{
    /**
     * Get the price for a single product.
     *
     * @param  Request  $request
     * @param  string  $sku
     * @return JsonResponse
     */
    public function getAction(Request $request, string $sku): JsonResponse
    {
        /** @var Collection $products */
        $products = Product::where('sku', $sku)->get();

        if ($products->isEmpty()) {
            return response()->json([
                'message' => __('Geen product gevonden voor het opgegeven productnummer.'),
                'success' => false,
                'code' => '404'
            ], 404);
        }

        /** @var CustomerContract $customer */
        $customer = $request->user();
        $customerNumber = $customer->getCompany()->getCustomerNumber();
        /** @var Response $response */
        $response = app('soap')->getProductPricesAndStocks($products, $customerNumber);

        if (empty($response->products)) {
            return response()->json([
                'message' => __('Geen prijs gevonden voor het opgegeven product.'),
                'success' => false,
                'code' => '404'
            ], 404);
        }

        /** @var Response\Product $product */
        $product = $response->products[0];

        return response()->json([
            'pricePer' => $product->price_per_string,
            'grossPrice' => $product->gross_price * $product->refactor,
            'netPrice' => $product->net_price * $product->refactor,
            'message' => $response->message,
            'code' => $response->code
        ], $response->code);
    }

    /**
     * Fetch the price for a customer.
     *
     * @param  FetchPriceRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAction(FetchPriceRequest $request)
    {
        /** @var Collection $products */
        $products = Product::whereIn('sku', $request->input('skus'))->get();

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'No products found for the given skus',
                'code' => '400'
            ], 400);
        }

        /** @var CustomerContract $customer */
        $customer = $request->user();
        $customerNumber = $customer->getCompany()->getCustomerNumber();
        $response = app('soap')->getProductPricesAndStocks($products, $customerNumber);

        return response()->json([
            'payload' => $response->products,
            'message' => $response->message,
            'code' => $response->code
        ], $response->code);
    }
}
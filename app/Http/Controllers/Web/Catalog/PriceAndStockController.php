<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Catalog;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use WTG\Catalog\PriceManager;
use WTG\Catalog\ProductManager;
use WTG\Catalog\StockManager;
use WTG\Contracts\Models\CustomerContract;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\FetchPriceRequest;
use WTG\Catalog\Model\Product;
use WTG\RestClient\Model\Rest\GetProductPrices\Response\Price;
use WTG\RestClient\Model\Rest\GetProductStocks\Response\Stock;

/**
 * Price and stock controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PriceAndStockController extends Controller
{
    protected PriceManager $priceManager;
    protected StockManager $stockManager;
    protected ProductManager $productManager;

    /**
     * PriceController constructor.
     *
     * @param ViewFactory $view
     * @param PriceManager $priceManager
     * @param StockManager $stockManager
     * @param ProductManager $productManager
     */
    public function __construct(
        ViewFactory $view,
        PriceManager $priceManager,
        StockManager $stockManager,
        ProductManager $productManager
    ) {
        parent::__construct($view);

        $this->priceManager = $priceManager;
        $this->stockManager = $stockManager;
        $this->productManager = $productManager;
    }

    /**
     * Get the price for a single product.
     *
     * @param Request $request
     * @param string $sku
     * @return JsonResponse
     */
    public function getAction(Request $request, string $sku): JsonResponse
    {
        $product = $this->productManager->find($sku);

        if (! $product) {
            return response()->json(
                [
                    'message' => __('Geen product gevonden voor het opgegeven productnummer.'),
                    'success' => false,
                    'code'    => '404',
                ],
                404
            );
        }

        /** @var CustomerContract $customer */
        $customer = $request->user();
        $customerNumber = $customer->getCompany()->getCustomerNumber();

        try {
            $price = $this->priceManager->fetchPrice($customerNumber, $sku, 1.0);
            $stock = $this->stockManager->fetchStock($sku);
        } catch (BindingResolutionException $e) {
            return response()->json(
                [
                    'message' => __('Geen prijs gevonden voor het opgegeven product.'),
                    'success' => false,
                    'code'    => '404',
                ],
                404
            );
        }

        return response()->json(
            [
                'price'   => $price,
                'stock'   => $stock,
                'message' => '',
                'code'    => 200,
            ],
            200
        );
    }

    /**
     * Fetch the price for a customer.
     *
     * @param FetchPriceRequest $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function postAction(FetchPriceRequest $request): JsonResponse
    {
        $skus = $request->input('skus', []);

        if (! is_array($skus) || empty($skus)) {
            return response()->json(
                [
                    'message' => 'Invalid value for parameter "skus"',
                    'code'    => '400',
                ],
                400
            );
        }

        /** @var CustomerContract $customer */
        $customer = $request->user();
        $customerNumber = $customer->getCompany()->getCustomerNumber();
        $products = $this->productManager->findAll($skus);

        $prices = $this->priceManager->fetchPrices($customerNumber, $products);
        $stocks = $this->stockManager->fetchStocks($products);

        $payload = $products->map(
            function (Product $product) use ($prices, $stocks) {
                $price = $prices->first(
                    function (Price $price) use ($product) {
                        return $price->sku === $product->getSku();
                    }
                );

                $stock = $stocks->first(
                    function (Stock $stock) use ($product) {
                        return $stock->sku === $product->getSku();
                    }
                );

                return [
                    'sku'   => $product->getSku(),
                    'price' => $price,
                    'stock' => $stock,
                ];
            }
        );

        return response()->json(
            [
                'payload' => $payload,
                'message' => '',
                'code'    => 200,
            ],
            200
        );
    }
}

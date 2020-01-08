<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Catalog;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use WTG\Catalog\PriceManager;
use WTG\Catalog\ProductManager;
use WTG\Contracts\Models\CustomerContract;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\FetchPriceRequest;
use WTG\Catalog\Model\Product;

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
     * @var PriceManager
     */
    protected PriceManager $priceManager;

    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * PriceController constructor.
     *
     * @param ViewFactory    $view
     * @param PriceManager   $priceManager
     * @param ProductManager $productManager
     */
    public function __construct(ViewFactory $view, PriceManager $priceManager, ProductManager $productManager)
    {
        parent::__construct($view);

        $this->priceManager = $priceManager;
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
        } catch (GuzzleException | BindingResolutionException $e) {
            return response()->json(
                [
                    'message' => __('Geen prijs gevonden voor het opgegeven product.'),
                    'success' => false,
                    'code'    => '404',
                ],
                404
            );
        }

        if (! $price) {
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
                'pricePer'   => __(''),
                'grossPrice' => $price->grossPrice * $product->getPriceFactor()->getPriceFactor(),
                'netPrice'   => $price->netPrice * $product->getPriceFactor()->getPriceFactor(),
                'action'     => $price->actionPrice,
                'message'    => '',
                'code'       => 200,
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
     * @throws GuzzleException
     */
    public function postAction(FetchPriceRequest $request): JsonResponse
    {
        $products = $request->input('skus', []);

        if (! is_array($products) || empty($products)) {
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

        $prices = $this->priceManager->fetchPrices($customerNumber, $products);

        return response()->json(
            [
                'payload' => $prices,
                'message' => '',
                'code'    => 200,
            ],
            200
        );
    }
}

<?php

namespace WTG\Soap\GetProductPricesAndStocks;

use Exception;
use WTG\Contracts\Models\ProductContract;
use WTG\Soap\AbstractService;
use Illuminate\Support\Collection;
use WTG\Services\Stock\Service as StockService;

/**
 * GetProductPricesAndStocks service.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPricesAndStocks
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Service extends AbstractService
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Collection
     */
    protected $products;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var StockService
     */
    protected $stockService;

    /**
     * Service constructor.
     *
     * @param  Request  $request
     * @param  Response  $response
     * @param  StockService  $stockService
     */
    public function __construct(Request $request, Response $response, StockService $stockService)
    {
        $this->request = $request;
        $this->response = $response;
        $this->stockService = $stockService;
    }

    /**
     * Run the service.
     *
     * @param  Collection  $products
     * @param  string  $customerId
     * @return Response
     */
    public function handle(Collection $products, string $customerId)
    {
        $this->products = $products;
        $this->customerId = $customerId;

        try {
            $this->buildRequest();
            $soapResponse = $this->sendRequest(
                "GetProductPricesAndStocksV2",
                $this->request
            );
            $this->buildResponse($soapResponse);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->response;
    }

    /**
     * Build the request.
     *
     * @return void
     */
    protected function buildRequest()
    {
        $this->request->debtorId = (string) $this->customerId;

        foreach ($this->products as $product) {
            /** @var Request\Product $requestProduct */
            $requestProduct = app()->make(Request\Product::class);
            $requestProduct->productId = (string) $product->getAttribute('sku');
            $requestProduct->unitId = (string) $product->getAttribute('sales_unit');

            $this->request->products[] = $requestProduct;
        }
    }

    /**
     * Build the response.
     *
     * @param  object  $soapResponse
     * @return void
     * @throws Exception
     */
    protected function buildResponse($soapResponse)
    {
        $soapProducts = $soapResponse->ProductPricesAndStocks->ProductPriceAndStockV2 ?? [];

        if (! is_array($soapProducts)) {
            $soapProducts = [ $soapProducts ];
        }

        foreach ($soapProducts as $soapProduct) {
            $productModel = $this->getProductModel($soapProduct->ProductId);

            $refactor = (float) $soapProduct->ConversionFactor;
            $grossPrice = (float) $soapProduct->GrossPrice;
            $netPrice = (float) $soapProduct->NettPrice;
            $pricePer = (float) $soapProduct->PricePer;
            
            /** @var Response\Product $product */
            $product = app()->make(Response\Product::class);
            $product->sku           = $soapProduct->ProductId;
            $product->sales_unit    = $soapProduct->UnitId;
            $product->quantity      = (float) $soapProduct->Quantity;
            $product->gross_price   = (float) (($grossPrice * $refactor) / $pricePer);
            $product->net_price     = (float) (($netPrice * $refactor) / $pricePer);
            $product->discount      = (float) $soapProduct->DiscountPerc;
            $product->price_per     = $pricePer;
            $product->price_unit    = $soapProduct->PriceUnitId;
            $product->stock         = (float) $soapProduct->QtyStock;
            $product->refactor      = $refactor;

            if ($product->price_unit === 'DAG') {
                $pricePerString = sprintf('Verhuurd per dag');
            } elseif ($product->sales_unit === $product->price_unit) {
                $pricePerString = sprintf('Prijs per %s',
                    unit_to_str($product->price_unit, false));
            } else {
                $pricePerString = sprintf('Prijs per %s van %s %s',
                    unit_to_str($product->sales_unit, false),
                    $product->refactor, unit_to_str($product->price_unit, $product->refactor > 1)
                );
            }

            $product->price_per_string = $pricePerString;

            if ($productModel->getStockDisplay() === 'S') {
                if ($product->stock > 0) {
                    $stockString = sprintf('<span class="d-none d-md-inline">Voorraad: </span>%s %s',
                        $product->stock, unit_to_str($product->sales_unit, $product->stock !== 1)
                    );
                } else {
                    $stockString = __('In bestelling, bel voor meer info');
                }
            } elseif ($productModel->getStockDisplay() === 'A') {
                $stockString = __('Levertijd in overleg');
            } elseif ($productModel->getStockDisplay() === 'V') {
                $stockString = __('Binnen 24/48 uur mits voor 16.00 besteld');
            } else {
                $stockString = '';
            }

            $product->stock_string = $stockString;

            $this->response->products[] = $product;
        }

        $this->response->code = 200;
        $this->response->message = 'Success';
    }

    /**
     * @param  string  $sku
     * @return ProductContract
     */
    public function getProductModel(string $sku): ProductContract
    {
        return app(ProductContract::class)->where('sku', $sku)->first();
    }
}

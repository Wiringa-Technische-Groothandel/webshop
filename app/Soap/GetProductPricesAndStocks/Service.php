<?php

namespace WTG\Soap\GetProductPricesAndStocks;

use Exception;
use WTG\Soap\AbstractService;
use Illuminate\Support\Collection;

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
     * Service constructor.
     */
    public function __construct()
    {
        $this->request = app()->make(Request::class);
        $this->response = app()->make(Response::class);
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
        $soapProducts = !empty($soapResponse->ProductPricesAndStocks) ?
            $soapResponse->ProductPricesAndStocks->ProductPriceAndStockV2 :
            [];

        if (! is_array($soapProducts)) {
            $soapProducts = [ $soapProducts ];
        }

        foreach ($soapProducts as $soapProduct) {
            /** @var Response\Product $product */
            $product = app()->make(Response\Product::class);
            $product->sku           = $soapProduct->ProductId;
            $product->sales_unit    = $soapProduct->UnitId;
            $product->quantity      = (float) $soapProduct->Quantity;
            $product->gross_price   = (float) $soapProduct->GrossPrice;
            $product->net_price     = (float) $soapProduct->NettPrice;
            $product->discount      = (float) $soapProduct->DiscountPerc;
            $product->price_per     = (float) $soapProduct->PricePer;
            $product->price_unit    = $soapProduct->PriceUnitId;
            $product->stock         = (float) $soapProduct->QtyStock;
            $product->refactor      = (int) $soapProduct->ConversionFactor;

            if ($product->sales_unit === $product->price_unit) {
                $pricePerString = sprintf('Prijs per %s %s',
                    $product->price_per, unit_to_str($product->sales_unit, $product->price_per > 1));
            } else {
                $pricePerString = sprintf('Prijs per %s %s van %s %s',
                    $product->price_per, unit_to_str($product->sales_unit, $product->price_per > 1),
                    $product->refactor, unit_to_str($product->price_unit, $product->refactor > 1)
                );
            }

            $product->price_per_string = $pricePerString;

            $this->response->products[] = $product;
        }

        $this->response->code = 200;
        $this->response->message = 'Success';
    }
}
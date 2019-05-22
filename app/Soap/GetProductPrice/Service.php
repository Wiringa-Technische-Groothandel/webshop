<?php

namespace WTG\Soap\GetProductPrice;

use Exception;
use WTG\Soap\AbstractService;
use WTG\Contracts\Models\ProductContract;

/**
 * GetProductPrice service.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPrice
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
     * @var string
     */
    protected $sku;

    /**
     * @var float
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $unit;

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
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @param  string  $customerId
     * @return Response
     */
    public function handle(ProductContract $product, float $quantity, string $customerId)
    {
        $this->sku = $product->sku();
        $this->unit = $product->salesUnit();
        $this->quantity = $quantity;
        $this->customerId = $customerId;

        try {
            $this->buildRequest();
            $soapResponse = $this->sendRequest(
                "GetProductPriceV2",
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
        $this->request->amount = $this->quantity;
        $this->request->debtorId = $this->customerId;
        $this->request->productId = $this->sku;
        $this->request->unitId = $this->unit;
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
        $soapProduct = $soapResponse->ProductPricesV2->ProductPriceV2;
        $soapPrice = $soapProduct->ProductPriceDetailsV2->ProductPriceDetailV2;

        /** @var Response\Product $product */
        $product = app()->make(Response\Product::class);
        $product->sku           = $soapProduct->ProductId;
        $product->sales_unit    = $soapProduct->UnitId;
        $product->quantity      = (float) $soapPrice->NumberRequested;
        $product->gross_price   = (float) $soapPrice->GrossPrice;
        $product->net_price     = (float) $soapPrice->NettPrice;
        $product->discount      = (float) $soapPrice->Discount;
        $product->discountType  = $soapPrice->DiscountOrigin;
        $product->price_per     = (float) $soapPrice->PricePer;
        $product->price_unit    = $soapPrice->PriceUnit;
        $product->refactor      = (int) $soapPrice->ConversionFactor;

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

        $this->response->product = $product;
        $this->response->code = 200;
        $this->response->message = 'Success';
    }
}
<?php

declare(strict_types=1);

namespace WTG\Soap\GetProducts;

use Exception;

use SimpleXMLElement;

use WTG\Soap\AbstractService;
use WTG\Soap\GetProducts\Response\Product;

/**
 * GetProducts service.
 *
 * @package     WTG\Soap
 * @subpackage  GetProducts
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
     * @var int
     */
    protected $productsPerRequest;

    /**
     * @var int
     */
    protected $startFromIndex;

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
     * @param  int  $productsPerRequest
     * @param  int  $startFromIndex
     * @return Response
     */
    public function handle(int $productsPerRequest = 20, int $startFromIndex = 1)
    {
        $this->productsPerRequest = $productsPerRequest;
        $this->startFromIndex = $startFromIndex;

        try {
            $this->buildRequest();
            $soapResponse = $this->sendRequest(
                "GetProductV2",
                $this->request
            );
            $this->buildResponse($soapResponse);
        } catch (\Exception $e) {
            \Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $this->response->message = $e->getMessage();
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
        $this->request->maxQuantity = $this->productsPerRequest;
        $this->request->indexFrom = $this->startFromIndex;
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
        if (empty($soapResponse->ProductHeaders)) {
            throw new \Exception("GetProducts returned no products.");
        }

        if (! isset($soapResponse->ProductHeaders->ProductHeaderV2)) {
            $this->response->code = 200;
            $this->response->message = 'Success';

            return;
        }

        $products = $soapResponse->ProductHeaders->ProductHeaderV2;

        foreach ($products as $product) {
            /** @var Response\Product $responseProduct */
            $responseProduct = app()->make(Response\Product::class);
            $responseProduct->name            = $product->ShortDescriptions->ProductShortDescriptionV2->Description;
            $responseProduct->sku             = $product->ProductId;
            $responseProduct->group           = $product->Categories->ProductCategoryV2->Groups->ProductGroupV2->ProdGrpId;
            $responseProduct->ean             = $product->EanCode;
            $responseProduct->blocked         = (bool) $product->Blocked;
            $responseProduct->inactive        = (bool) $product->Inactive;
            $responseProduct->discontinued    = (bool) $product->Discontinued;
            $responseProduct->vat             = (float) $product->VatPercentage;
            $responseProduct->sales_unit      = $product->UnitId;
            $responseProduct->packing_unit    = $product->PackagingUnitId;
            $responseProduct->width           = (float) $product->ProductWidthCm;
            $responseProduct->length          = (float) $product->ProductLengthCm;
            $responseProduct->weight          = (float) $product->ProductWeightKg;
            $responseProduct->height          = (float) $product->ProductHeightCm;
            $responseProduct->stock_display   = $product->StockDisplay ?: 'S';

            $this->assignAttributes($responseProduct, $product);

            $this->response->products[] = $responseProduct;
        }

        $this->response->code = 200;
        $this->response->message = 'Success';
    }

    /**
     * Assign product attributes.
     *
     * @param  Product  $responseProduct
     * @param  SimpleXMLElement  $product
     * @return void
     */
    public function assignAttributes(Product &$responseProduct, $product): void
    {
        $attributes = optional($product->Attributes)->ProductAttributeV2;

        if (empty($attributes)) {
            return;
        }

        if (! is_array($attributes)) {
            $attributes = [ $attributes ];
        }

        foreach ($attributes as $attribute) {
            $value = $attribute->AttrValues->ProductAttributeValueV2->NativeDescription;

            switch ($attribute->AttributeId) {
                case 'FAB':
                    $responseProduct->supplier_code = $value;
                    break;
                case 'MRK':
                    $responseProduct->brand = $value;
                    break;
                case 'WBG':
                    $responseProduct->series = $value;
                    break;
                case 'WBS':
                    $responseProduct->type = $value;
                    break;
                case 'TBH':
                    $responseProduct->related = $value;
                    break;
                case 'WEB':
                    $responseProduct->webshop = (bool) $value;
                    break;
                case 'ATT':
                    $responseProduct->keywords = $value;
                    break;
            }
        }
    }
}
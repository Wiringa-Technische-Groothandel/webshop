<?php

declare(strict_types=1);

namespace WTG\Soap\GetProduct;

use Exception;
use Log;
use SimpleXMLElement;
use WTG\Soap\AbstractService;
use WTG\Soap\GetProduct\Response\Product;

/**
 * GetProduct service.
 *
 * @package     WTG\Soap
 * @subpackage  GetProduct
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
    protected $sku;

    /**
     * @var int
     */
    protected $salesUnit;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->request = app(Request::class);
        $this->response = app(Response::class);
    }

    /**
     * Run the service.
     *
     * @param string $sku
     * @param string $salesUnit
     * @return Response
     */
    public function handle(string $sku, string $salesUnit)
    {
        $this->sku = $sku;
        $this->salesUnit = $salesUnit;

        try {
            $this->buildRequest();
            $soapResponse = $this->sendRequest(
                "GetProductV2",
                $this->request
            );
            $this->buildResponse($soapResponse);
        } catch (Exception $e) {
            Log::error(
                $e->getMessage(),
                [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

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
        $this->request->productId = $this->sku;
        $this->request->unitId = $this->salesUnit;
    }

    /**
     * Build the response.
     *
     * @param object $soapResponse
     * @return void
     * @throws Exception
     */
    protected function buildResponse($soapResponse)
    {
        if (empty($soapResponse->ProductHeaders)) {
            throw new Exception("GetProduct returned no results.");
        }

        if (! isset($soapResponse->ProductHeaders->ProductHeaderV2)) {
            $this->response->code = 200;
            $this->response->message = 'Success';

            return;
        }

        $product = $soapResponse->ProductHeaders->ProductHeaderV2;

        /** @var Response\Product $responseProduct */
        $responseProduct = app()->make(Response\Product::class);
        $responseProduct->name = $product->ShortDescriptions->ProductShortDescriptionV2->Description;
        $responseProduct->sku = $product->ProductId;
        $responseProduct->group = $product->Categories->ProductCategoryV2->Groups->ProductGroupV2->ProdGrpId;
        $responseProduct->ean = $product->EanCode;
        $responseProduct->blocked = (bool)$product->Blocked;
        $responseProduct->inactive = (bool)$product->Inactive;
        $responseProduct->discontinued = (bool)$product->Discontinued;
        $responseProduct->vat = (float)$product->VatPercentage;
        $responseProduct->sales_unit = $product->UnitId;
        $responseProduct->packing_unit = $product->PackagingUnitId;
        $responseProduct->width = (float)$product->ProductWidthCm;
        $responseProduct->length = (float)$product->ProductLengthCm;
        $responseProduct->weight = (float)$product->ProductWeightKg;
        $responseProduct->height = (float)$product->ProductHeightCm;
        $responseProduct->stock_display = $product->StockDisplay ?: 'S';

        $this->assignAttributes($responseProduct, $product);

        $this->response->product = $responseProduct;

        $this->response->code = 200;
        $this->response->message = 'Success';
    }

    /**
     * Assign product attributes.
     *
     * @param Product $responseProduct
     * @param SimpleXMLElement $product
     * @return void
     */
    public function assignAttributes(Product &$responseProduct, $product): void
    {
        $attributes = optional($product->Attributes)->ProductAttributeV2;

        if (empty($attributes)) {
            return;
        }

        if (! is_array($attributes)) {
            $attributes = [$attributes];
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
                    $responseProduct->webshop = (bool)$value;
                    break;
                case 'ATT':
                    $responseProduct->keywords = $value;
                    break;
            }
        }
    }
}

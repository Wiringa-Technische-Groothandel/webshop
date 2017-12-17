<?php

namespace WTG\Soap;

use WTG\Contracts\ProductContract;
use Illuminate\Support\Collection;
use WTG\Services\AbstractSoapService;

/**
 * Soap service.
 *
 * @package     WTG
 * @subpackage  Soap
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Service extends AbstractSoapService
{
    /**
     * Calls: GetProducts
     *
     * @soap
     * @param  int  $productsPerRequest
     * @param  int  $startFromIndex
     * @return GetProducts\Response
     */
    public function getAllProducts(int $productsPerRequest = 20, int $startFromIndex = 1)
    {
        /** @var GetProducts\Service $service */
        $service = app()->make(GetProducts\Service::class);
        return $service->handle($productsPerRequest, $startFromIndex);
    }

    /**
     * Calls: GetProductPrice
     *
     * @soap
     * @param  ProductContract  $product
     * @param  float  $quantity
     * @param  string  $customerId
     * @return GetProductPrice\Response
     */
    public function getProductPrice(ProductContract $product, float $quantity, string $customerId)
    {
        /** @var GetProductPrice\Service $service */
        $service = app()->make(GetProductPrice\Service::class);
        return $service->handle($product, $quantity, $customerId);
    }

    /**
     * Calls: GetProductPricesAndStocks
     *
     * @soap
     * @param  Collection  $products
     * @param  string  $customerId
     * @return GetProductPricesAndStocks\Response
     */
    public function getProductPricesAndStocks(Collection $products, string $customerId)
    {
        /** @var GetProductPricesAndStocks\Service $service */
        $service = app()->make(GetProductPricesAndStocks\Service::class);
        return $service->handle($products, $customerId);
    }
}
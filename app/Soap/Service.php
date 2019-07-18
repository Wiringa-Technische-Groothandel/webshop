<?php

namespace WTG\Soap;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use WTG\Services\AbstractSoapService;
use WTG\Contracts\Models\ProductContract;

/**
 * Soap service.
 *
 * @package     WTG\Soap
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
    public function getProducts(int $productsPerRequest = 20, int $startFromIndex = 1)
    {
        $service = app(GetProducts\Service::class);
        return $service->handle($productsPerRequest, $startFromIndex);
    }

    /**
     * Calls: GetProducts
     *
     * @soap
     * @param string $sku
     * @param string $salesUnit
     * @return GetProduct\Response
     */
    public function getProduct(string $sku, string $salesUnit)
    {
        $service = app(GetProduct\Service::class);
        return $service->handle($sku, $salesUnit);
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
        $service = app(GetProductPrice\Service::class);
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
        $service = app(GetProductPricesAndStocks\Service::class);
        return $service->handle($products, $customerId);
    }

    /**
     * Calls: GetSalesOrderHeaderCount
     *
     * @soap
     * @param  string  $customerId
     * @param  Carbon|null  $from
     * @param  Carbon|null  $to
     * @return GetOrderCount\Response
     */
    public function getOrderCount(string $customerId, ?Carbon $from = null, ?Carbon $to = null)
    {
        $service = app(GetOrderCount\Service::class);
        return $service->handle($customerId, $from, $to);
    }

    /**
     * Calls: ExportProducts
     *
     * @soap
     * @return ExportProducts\Response
     */
    public function exportProducts()
    {
        $service = app(ExportProducts\Service::class);
        return $service->handle();
    }
}
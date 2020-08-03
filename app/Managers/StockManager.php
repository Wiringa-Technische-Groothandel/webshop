<?php

declare(strict_types=1);

namespace WTG\Managers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Rest\ErrorResponse;
use WTG\RestClient\Model\Rest\GetProductStocks\Request as GetProductStocksRequest;
use WTG\RestClient\Model\Rest\GetProductStocks\Response as GetProductStocksResponse;

/**
 * Stock manager.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class StockManager
{
    /**
     * @var RestManager
     */
    protected RestManager $restManager;

    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * PriceManager constructor.
     *
     * @param RestManager $restManager
     * @param ProductManager $productManager
     */
    public function __construct(RestManager $restManager, ProductManager $productManager)
    {
        $this->restManager = $restManager;
        $this->productManager = $productManager;
    }

    /**
     * Fetch stocks for multiple products.
     *
     * @param Collection $products
     * @return Collection
     * @throws BindingResolutionException
     */
    public function fetchStocks(Collection $products): Collection
    {
        $request = new GetProductStocksRequest();

        foreach ($products->all() as $product) {
            $request->addProduct($product);
        }

        /** @var GetProductStocksResponse $response */
        $response = $this->restManager->handle($request);

        return ($response instanceof ErrorResponse) ? collect() : $response->stocks;
    }

    /**
     * Fetch the stock for a product.
     *
     * @param string $sku
     * @return null|GetProductStocksResponse\Stock
     * @throws BindingResolutionException
     */
    public function fetchStock(string $sku): ?GetProductStocksResponse\Stock
    {
        $request = new GetProductStocksRequest();

        $product = $this->productManager->find($sku);
        $request->addProduct($product);

        /** @var GetProductStocksResponse $response */
        $response = $this->restManager->handle($request);

        return ($response instanceof ErrorResponse) ? null : $response->stocks->first();
    }
}

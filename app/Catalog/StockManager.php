<?php

declare(strict_types=1);

namespace WTG\Catalog;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Rest\GetProductStocks\Request as GetProductStocksRequest;
use WTG\RestClient\Model\Rest\GetProductStocks\Response as GetProductStocksResponse;
use WTG\RestClient\RestManager;

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
     * @param RestManager    $restManager
     * @param ProductManager $productManager
     */
    public function __construct(RestManager $restManager, ProductManager $productManager)
    {
        $this->restManager = $restManager;
        $this->productManager = $productManager;
    }

    /**
     * Fetch prices for multiple products.
     *
     * @param array $skus
     * @return Collection
     * @throws GuzzleException
     * @throws BindingResolutionException
     */
    public function fetchStocks(array $skus): Collection
    {
        $request = new GetProductStocksRequest();

        foreach ( $skus as $sku ) {
            $product = $this->productManager->find($sku);

            $request->addProduct($product);
        }

        /** @var GetProductStocksResponse $response */
        $response = $this->restManager->handle($request);

        return $response->getStocks();
    }

    /**
     * Fetch prices for multiple products.
     *
     * @param string $sku
     * @return null|GetProductStocksResponse\Stock
     * @throws BindingResolutionException
     * @throws GuzzleException
     */
    public function fetchStock(string $sku): ?GetProductStocksResponse\Stock
    {
        $request = new GetProductStocksRequest();

        $product = $this->productManager->find($sku);
        $request->addProduct($product);

        /** @var GetProductStocksResponse $response */
        $response = $this->restManager->handle($request);

        return $response->getStocks()->first();
    }
}

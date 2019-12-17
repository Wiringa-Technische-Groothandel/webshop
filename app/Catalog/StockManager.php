<?php

declare(strict_types=1);

namespace WTG\Catalog;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use WTG\Models\Product;
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
     * PriceManager constructor.
     *
     * @param RestManager $restManager
     */
    public function __construct(RestManager $restManager)
    {
        $this->restManager = $restManager;
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

        foreach ($skus as $sku) {
            $product = Product::findBySku((string)$sku, true);

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

        $product = Product::findBySku((string)$sku, true);
        $request->addProduct($product);

        /** @var GetProductStocksResponse $response */
        $response = $this->restManager->handle($request);

        return $response->getStocks()->first();
    }
}

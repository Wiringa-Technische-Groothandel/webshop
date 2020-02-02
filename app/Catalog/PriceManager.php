<?php

declare(strict_types=1);

namespace WTG\Catalog;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Rest\GetProductPrices\Request as GetProductPricesRequest;
use WTG\RestClient\Model\Rest\GetProductPrices\Response as GetProductPricesResponse;
use WTG\RestClient\RestManager;

/**
 * Price manager.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PriceManager
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
     * Fetch prices for multiple products.
     *
     * @param string $debtor
     * @param Collection $products
     * @return Collection
     * @throws GuzzleException
     * @throws BindingResolutionException
     */
    public function fetchPrices(string $debtor, Collection $products): Collection
    {
        $request = new GetProductPricesRequest();
        $request->setDebtorCode($debtor);

        foreach ($products->all() as $product) {
            $request->addProduct($product, 1);
        }

        /** @var GetProductPricesResponse $response */
        $response = $this->restManager->handle($request);

        return $response->getPrices();
    }

    /**
     * Fetch prices for multiple products.
     *
     * @param string $debtor
     * @param string $sku
     * @param float $qty
     * @return null|GetProductPricesResponse\Price
     * @throws BindingResolutionException
     * @throws GuzzleException
     */
    public function fetchPrice(string $debtor, string $sku, float $qty): ?GetProductPricesResponse\Price
    {
        $request = new GetProductPricesRequest();
        $request->setDebtorCode($debtor);

        $product = $this->productManager->find($sku);
        $request->addProduct($product, $qty);

        /** @var GetProductPricesResponse $response */
        $response = $this->restManager->handle($request);

        return $response->getPrices()->first();
    }
}

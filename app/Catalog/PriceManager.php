<?php

declare(strict_types=1);

namespace WTG\Catalog;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use WTG\Models\Product;
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
     * @param string $debtor
     * @param array $products Product model or sku
     * @return Collection
     * @throws GuzzleException
     * @throws BindingResolutionException
     */
    public function fetchPrices(string $debtor, array $products): Collection
    {
        $request = new GetProductPricesRequest();
        $request->setDebtorCode($debtor);

        foreach ($products as $product) {
            if (! $product instanceof Product) {
                $product = Product::findBySku((string)$product, true);
            }

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

        $product = Product::findBySku((string)$sku, true);
        $request->addProduct($product, $qty);

        /** @var GetProductPricesResponse $response */
        $response = $this->restManager->handle($request);

        return $response->getPrices()->first();
    }
}

<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductStocks;

use WTG\Models\Product;
use WTG\RestClient\Api\Model\RequestInterface;
use WTG\RestClient\Model\Rest\AbstractRequest;

/**
 * GetProductStocks request model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest implements RequestInterface
{
    /**
     * @var array
     */
    protected array $products;

    /**
     * Add a product for price fetching.
     *
     * @param Product $product
     * @return $this
     */
    public function addProduct(Product $product): self
    {
        $this->products[] = [
            'sku'       => $product->getSku(),
            'salesUnit' => $product->getSalesUnit(),
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return sprintf('%s/requestProductStock', config('wtg.rest.admin_code'));
    }

    /**
     * Request type.
     *
     * @return string
     */
    public function type(): string
    {
        return self::HTTP_REQUEST_TYPE_POST;
    }

    /**
     * @inheritDoc
     */
    public function body(): ?array
    {
        $products = [];
        foreach ($this->products as $product) {
            $products[] = [
                'productIdentifier' => [
                    'productCode' => (string)$product['sku'],
                ],
                'unitCode'          => $product['salesUnit'],
            ];
        }

        return [
            'limit'              => 30,
            'offset'             => 0,
            'productIdentifiers' => $products,
        ];
    }
}

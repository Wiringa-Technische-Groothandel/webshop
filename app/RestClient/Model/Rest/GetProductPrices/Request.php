<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductPrices;

use WTG\Catalog\Model\Product;
use WTG\RestClient\Api\Model\RequestInterface;
use WTG\RestClient\Model\Rest\AbstractRequest;

/**
 * GetProductPrices request model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest implements RequestInterface
{
    /**
     * @var array
     */
    protected array $products = [];

    /**
     * @var string
     */
    protected string $debtorCode;

    /**
     * Add a product for price fetching.
     *
     * @param Product $product
     * @param float $quantity
     * @return $this
     */
    public function addProduct(Product $product, float $quantity = 1.0): self
    {
        $this->products[] = [
            'sku'       => $product->getSku(),
            'salesUnit' => $product->getSalesUnit(),
            'quantity'  => $quantity,
        ];

        return $this;
    }

    /**
     * Set the debtor code.
     *
     * @param string $debtorCode
     * @return $this
     */
    public function setDebtorCode(string $debtorCode): self
    {
        $this->debtorCode = $debtorCode;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return sprintf('%s/requestProductPrices', config('wtg.rest.admin_code'));
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
                'quantity'          => $product['quantity'],
                'unitCode'          => $product['salesUnit'],
            ];
        }

        return [
            'limit'              => 30,
            'offset'             => 0,
            'debtorCodeFrom'     => $this->debtorCode,
            'debtorCodeTill'     => $this->debtorCode,
            'productIdentifiers' => $products,
        ];
    }
}

<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProducts;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\Rest\GetProductsInterface;
use WTG\RestClient\Model\Parser\ProductParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetProducts response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse implements GetProductsInterface
{
    /**
     * @var ProductParser
     */
    protected ProductParser $productParser;

    /**
     * Response constructor.
     *
     * @param GuzzleResponseInterface $guzzleResponse
     * @param LogManager $logManager
     * @param ProductParser $productParser
     */
    public function __construct(
        GuzzleResponseInterface $guzzleResponse,
        LogManager $logManager,
        ProductParser $productParser
    ) {
        parent::__construct($guzzleResponse, $logManager);

        $this->productParser = $productParser;
    }

    /**
     * Get products from the response.
     *
     * @return Collection
     */
    public function getProducts(): Collection
    {
        $products = collect();

        foreach ($this->toArray() as $item) {
            $products->push(
                $this->productParser->parse($item)
            );
        }

        return $products;
    }

    /**
     * Get raw, unmapped products from the response.
     *
     * @return Collection
     */
    public function getRawProducts(): Collection
    {
        return collect($this->toArray());
    }
}

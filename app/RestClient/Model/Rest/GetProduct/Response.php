<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProduct;

use Illuminate\Log\LogManager;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\Rest\GetProductInterface;
use WTG\RestClient\Model\Parser\ProductsParser;
use WTG\RestClient\Model\Rest\AbstractResponse;
use WTG\RestClient\Model\Rest\ProductResponse;

/**
 * GetProduct response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse implements GetProductInterface
{
    /**
     * @var ProductsParser
     */
    protected ProductsParser $productsParser;

    /**
     * Response constructor.
     *
     * @param GuzzleResponseInterface $guzzleResponse
     * @param LogManager $logManager
     * @param ProductsParser $productsParser
     */
    public function __construct(
        GuzzleResponseInterface $guzzleResponse,
        LogManager $logManager,
        ProductsParser $productsParser
    ) {
        parent::__construct($guzzleResponse, $logManager);

        $this->productsParser = $productsParser;
    }

    /**
     * Get products from the response.
     *
     * @return ProductResponse
     */
    public function getProduct(): ProductResponse
    {
        return $this->productsParser->parse($this->toArray());
    }

    /**
     * Get raw, unmapped product from the response.
     *
     * @return array
     */
    public function getRawProduct(): array
    {
        return $this->toArray();
    }
}

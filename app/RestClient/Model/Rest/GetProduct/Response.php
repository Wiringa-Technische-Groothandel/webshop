<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProduct;

use Illuminate\Log\LogManager;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\Rest\GetProductInterface;
use WTG\RestClient\Model\Parser\ProductParser;
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
     * @return ProductResponse
     */
    public function getProduct(): ProductResponse
    {
        return $this->productParser->parse($this->toArray());
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

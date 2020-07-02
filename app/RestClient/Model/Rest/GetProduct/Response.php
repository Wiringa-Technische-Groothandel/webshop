<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProduct;

use Illuminate\Log\LogManager;
use WTG\RestClient\Model\Parser\ProductParser;
use WTG\RestClient\Model\Rest\AbstractResponse;
use WTG\RestClient\Model\Rest\ProductResponse;

/**
 * GetProduct response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public ProductResponse $product;

    protected ProductParser $productParser;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param LogManager $logManager
     * @param ProductParser $productParser
     */
    public function __construct(
        array $responseData,
        LogManager $logManager,
        ProductParser $productParser
    ) {
        $this->productParser = $productParser;

        parent::__construct($responseData, $logManager);
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $this->product = $this->productParser->parse($this->responseData);
    }
}

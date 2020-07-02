<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProducts;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Parser\ProductParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetProducts response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public Collection $products;

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
        $products = collect();

        foreach ($this->responseData as $item) {
            $products->push(
                $this->productParser->parse($item)
            );
        }

        $this->products = $products;
    }
}

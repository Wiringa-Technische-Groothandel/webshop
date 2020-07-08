<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductPrices;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Parser\PriceParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetProductPrices response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public Collection $prices;

    protected PriceParser $priceParser;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param LogManager $logManager
     * @param PriceParser $priceParser
     */
    public function __construct(
        array $responseData,
        LogManager $logManager,
        PriceParser $priceParser
    ) {
        $this->priceParser = $priceParser;

        parent::__construct($responseData, $logManager);
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $prices = collect();

        foreach ($this->responseData['resultData'] ?? [] as $price) {
            $prices->push(
                $this->priceParser->parse($price)
            );
        }

        $this->prices = $prices;
    }
}

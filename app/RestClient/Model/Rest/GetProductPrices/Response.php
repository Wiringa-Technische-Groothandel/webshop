<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductPrices;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\Rest\GetProductPricesInterface;
use WTG\RestClient\Model\Parser\PriceParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetProductPrices response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse implements GetProductPricesInterface
{
    /**
     * @var PriceParser
     */
    protected PriceParser $priceParser;

    /**
     * Response constructor.
     *
     * @param GuzzleResponseInterface $guzzleResponse
     * @param LogManager $logManager
     * @param PriceParser $priceParser
     */
    public function __construct(
        GuzzleResponseInterface $guzzleResponse,
        LogManager $logManager,
        PriceParser $priceParser
    ) {
        parent::__construct($guzzleResponse, $logManager);

        $this->priceParser = $priceParser;
    }

    /**
     * Get products from the response.
     *
     * @return Collection
     */
    public function getPrices(): Collection
    {
        $prices = collect();

        foreach ($this->toArray()['resultData'] as $price) {
            $prices->push(
                $this->priceParser->parse($price)
            );
        }

        return $prices;
    }

    /**
     * Get raw, unmapped product from the response.
     *
     * @return Collection
     */
    public function getRawPrices(): Collection
    {
        return collect($this->toArray());
    }
}

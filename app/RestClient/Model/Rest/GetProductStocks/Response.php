<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductStocks;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\Rest\GetProductStocksInterface;
use WTG\RestClient\Model\Parser\StockParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetProductStocks response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse implements GetProductStocksInterface
{
    /**
     * @var StockParser
     */
    protected StockParser $stockParser;

    /**
     * Response constructor.
     *
     * @param GuzzleResponseInterface $guzzleResponse
     * @param LogManager $logManager
     * @param StockParser $stockParser
     */
    public function __construct(
        GuzzleResponseInterface $guzzleResponse,
        LogManager $logManager,
        StockParser $stockParser
    ) {
        parent::__construct($guzzleResponse, $logManager);

        $this->stockParser = $stockParser;
    }

    /**
     * Get products from the response.
     *
     * @return Collection
     */
    public function getStocks(): Collection
    {
        $prices = collect();

        foreach ($this->toArray()['resultData'] as $price) {
            $prices->push(
                $this->stockParser->parse($price)
            );
        }

        return $prices;
    }

    /**
     * Get raw, unmapped product from the response.
     *
     * @return Collection
     */
    public function getRawStocks(): Collection
    {
        return collect($this->toArray());
    }
}

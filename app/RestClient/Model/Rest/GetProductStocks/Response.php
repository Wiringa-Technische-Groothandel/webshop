<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductStocks;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Parser\StockParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetProductStocks response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public Collection $stocks;

    protected StockParser $stockParser;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param LogManager $logManager
     * @param StockParser $stockParser
     */
    public function __construct(
        array $responseData,
        LogManager $logManager,
        StockParser $stockParser
    ) {
        $this->stockParser = $stockParser;

        parent::__construct($responseData, $logManager);
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $stocks = collect();
        $resultData = $this->responseData['resultData'] ?? [];

        foreach ($resultData as $stock) {
            $stocks->push(
                $this->stockParser->parse($stock)
            );
        }

        $this->stocks = $stocks;
    }
}

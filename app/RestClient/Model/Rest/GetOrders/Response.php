<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetOrders;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Parser\OrderParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetOrders response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public Collection $orders;

    protected OrderParser $orderParser;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param LogManager $logManager
     * @param OrderParser $orderParser
     */
    public function __construct(
        array $responseData,
        LogManager $logManager,
        OrderParser $orderParser
    ) {
        $this->orderParser = $orderParser;

        parent::__construct($responseData, $logManager);
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $orders = collect();

        foreach ($this->responseData as $item) {
            $orders->push(
                $this->orderParser->parse($item)
            );
        }

        $this->orders = $orders;
    }
}

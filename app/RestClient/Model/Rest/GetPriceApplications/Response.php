<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetPriceApplications;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use WTG\RestClient\Model\Parser\PriceFactorParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetPriceApplications response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public Collection $priceFactors;

    protected PriceFactorParser $parser;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param LogManager $logManager
     * @param PriceFactorParser $priceFactorParser
     */
    public function __construct(
        array $responseData,
        LogManager $logManager,
        PriceFactorParser $priceFactorParser
    ) {
        $this->parser = $priceFactorParser;

        parent::__construct($responseData, $logManager);
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $factors = collect();

        foreach ($this->responseData as $item) {
            $factors->push(
                $this->parser->parse($item)
            );
        }

        $this->priceFactors = $factors;
    }
}

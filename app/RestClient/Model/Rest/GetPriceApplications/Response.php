<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetPriceApplications;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\Rest\GetPriceFactorsInterface;
use WTG\RestClient\Model\Parser\PriceFactorParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetPriceApplications response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse implements GetPriceFactorsInterface
{
    /**
     * @var PriceFactorParser
     */
    protected PriceFactorParser $parser;

    /**
     * Response constructor.
     *
     * @param GuzzleResponseInterface $guzzleResponse
     * @param LogManager $logManager
     * @param PriceFactorParser $priceFactorParser
     */
    public function __construct(
        GuzzleResponseInterface $guzzleResponse,
        LogManager $logManager,
        PriceFactorParser $priceFactorParser
    ) {
        parent::__construct($guzzleResponse, $logManager);

        $this->parser = $priceFactorParser;
    }

    /**
     * Get price factors from the response.
     *
     * @return Collection
     */
    public function getPriceFactors(): Collection
    {
        $factors = collect();

        foreach ($this->toArray() as $item) {
            $factors->push(
                $this->parser->parse($item)
            );
        }

        return $factors;
    }

    /**
     * Get raw, unmapped products from the response.
     *
     * @return Collection
     */
    public function getRawPriceFactors(): Collection
    {
        return collect($this->toArray());
    }
}

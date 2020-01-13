<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductGroups;

use Illuminate\Log\LogManager;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\Rest\GetProductGroupsInterface;
use WTG\RestClient\Model\Parser\GroupParser;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetProductGroups response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse implements GetProductGroupsInterface
{
    /**
     * @var GroupParser
     */
    protected GroupParser $groupParser;

    /**
     * Response constructor.
     *
     * @param GuzzleResponseInterface $guzzleResponse
     * @param LogManager $logManager
     * @param GroupParser $groupParser
     */
    public function __construct(
        GuzzleResponseInterface $guzzleResponse,
        LogManager $logManager,
        GroupParser $groupParser
    ) {
        parent::__construct($guzzleResponse, $logManager);

        $this->groupParser = $groupParser;
    }

    /**
     * Get product groups from the response.
     *
     * @return Collection
     */
    public function getGroups(): Collection
    {
        $prices = collect();

        foreach ($this->toArray() as $price) {
            $prices->push(
                $this->groupParser->parse($price)
            );
        }

        return $prices;
    }

    /**
     * Get raw, unmapped product from the response.
     *
     * @return Collection
     */
    public function getRawGroups(): Collection
    {
        return collect($this->toArray());
    }
}

<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest;

use Illuminate\Log\LogManager;
use Psr\Http\Message\ResponseInterface as GuzzleResponseInterface;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * Abstract response model.
 *
 * @package     WTG\RestClient\Model\Rest
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class AbstractResponse implements ResponseInterface
{
    protected array $responseData;
    protected LogManager $logManager;

    /**
     * Response constructor.
     *
     * @param array $responseData
     * @param LogManager $logManager
     */
    public function __construct(array $responseData, LogManager $logManager)
    {
        $this->responseData = $responseData;
        $this->logManager = $logManager;

        $this->parse();
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->responseData;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->responseData);
    }
}

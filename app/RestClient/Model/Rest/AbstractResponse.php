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
    /**
     * @var GuzzleResponseInterface
     */
    protected GuzzleResponseInterface $guzzleResponse;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * Response constructor.
     *
     * @param GuzzleResponseInterface $guzzleResponse
     * @param LogManager $logManager
     */
    public function __construct(GuzzleResponseInterface $guzzleResponse, LogManager $logManager)
    {
        $this->guzzleResponse = $guzzleResponse;
        $this->logManager = $logManager;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (! $this->isSuccess()) {
            $this->logManager->warning(
                sprintf(
                    '[WTG RestClient] Warning: Failed request with code %d, response: %s',
                    $this->getStatusCode(),
                    $this->getBody()
                )
            );

            return [];
        }

        return json_decode((string)$this->getGuzzleResponse()->getBody(), true) ?: [];
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getStatusCode() >= 200 && $this->getStatusCode() < 300;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->getGuzzleResponse()->getStatusCode();
    }

    /**
     * @return GuzzleResponseInterface
     */
    public function getGuzzleResponse(): GuzzleResponseInterface
    {
        return $this->guzzleResponse;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return (string)$this->getGuzzleResponse()->getBody();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}

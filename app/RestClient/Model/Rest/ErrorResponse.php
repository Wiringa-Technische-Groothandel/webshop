<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest;

use GuzzleHttp\Exception\GuzzleException;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * Rest client error response.
 *
 * @package     WTG\RestClient\Model\Rest
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ErrorResponse implements ResponseInterface
{
    public string $message;

    protected GuzzleException $exception;

    /**
     * ErrorResponse constructor.
     *
     * @param GuzzleException $exception
     */
    public function __construct(GuzzleException $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $this->message = $this->exception->getMessage();
    }

    /**
     * @inheritDoc
     */
    public function __toArray(): array
    {
        return [
            'message' => $this->message
        ];
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return json_encode($this->__toArray());
    }
}

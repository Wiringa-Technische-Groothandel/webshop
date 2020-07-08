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

    /**
     * ErrorResponse constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;

        $this->parse();
    }

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        //
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

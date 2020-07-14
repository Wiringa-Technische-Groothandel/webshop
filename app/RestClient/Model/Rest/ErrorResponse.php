<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest;

use Throwable;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * Rest client error response.
 *
 * @package     WTG\RestClient\Model\Rest
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ErrorResponse implements ResponseInterface
{
    public Throwable $exception;

    /**
     * ErrorResponse constructor.
     *
     * @param Throwable $exception
     */
    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;

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
            'message' => $this->exception->getMessage()
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

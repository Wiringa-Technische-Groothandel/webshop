<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest;

use WTG\RestClient\Api\Model\RequestInterface;

/**
 * Abstract request model.
 *
 * @package WTG\RestClient\Model\Rest
 */
abstract class AbstractRequest implements RequestInterface
{
    public int $timeout = 5;

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'headers' => $this->headers(),
            'body'    => $this->body(),
            'type'    => $this->type(),
            'params'  => $this->params(),
            'path'    => $this->path(),
        ];
    }

    /**
     * Request headers.
     *
     * @return array|null
     */
    public function headers(): ?array
    {
        return [];
    }

    /**
     * Request body.
     *
     * @return array|null
     */
    public function body(): ?array
    {
        return [];
    }

    /**
     * Request type. (Default: GET)
     *
     * @return string
     */
    public function type(): string
    {
        return self::HTTP_REQUEST_TYPE_GET;
    }

    /**
     * Request URL params.
     *
     * @return array|null
     */
    public function params(): ?array
    {
        return [];
    }

    /**
     * Request path.
     *
     * @return string
     */
    abstract public function path(): string;
}

<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model;

/**
 * Response interface.
 *
 * @api
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface ResponseInterface
{
    /**
     * Transform the response into an array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return string
     */
    public function getBody(): string;

    /**
     * @return array
     */
    public function __toArray(): array;

    /**
     * @return string
     */
    public function __toString(): string;
}

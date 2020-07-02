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
     * Parse the response data.
     *
     * @return void
     */
    public function parse(): void;

    /**
     * @return array
     */
    public function __toArray(): array;

    /**
     * @return string
     */
    public function __toString(): string;
}

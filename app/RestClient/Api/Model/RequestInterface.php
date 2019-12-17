<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Request interface.
 *
 * @api
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface RequestInterface extends Arrayable
{
    public const HTTP_REQUEST_TYPE_GET = 'get';
    public const HTTP_REQUEST_TYPE_POST = 'post';
    public const HTTP_REQUEST_TYPE_PUT = 'put';
    public const HTTP_REQUEST_TYPE_DELETE = 'delete';

    /**
     * Request type.
     *
     * Possible values: get, post, put, delete
     *
     * @return string
     */
    public function type(): string;

    /**
     * Additional request headers.
     *
     * @return null|array
     */
    public function headers(): ?array;

    /**
     * Request parameters.
     *
     * @return null|array
     */
    public function params(): ?array;

    /**
     * Request body.
     *
     * @return null|array
     */
    public function body(): ?array;

    /**
     * Request path.
     *
     * @return string
     */
    public function path(): string;
}

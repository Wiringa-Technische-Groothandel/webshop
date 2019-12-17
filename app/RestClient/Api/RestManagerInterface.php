<?php

declare(strict_types=1);

namespace WTG\RestClient\Api;

use WTG\RestClient\Api\Model\RequestInterface;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * Rest manager interface.
 *
 * @api
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface RestManagerInterface
{
    /**
     * Handle the provided request.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface;
}

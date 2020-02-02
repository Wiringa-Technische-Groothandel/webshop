<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model\Rest;

use WTG\RestClient\Api\Model\ResponseInterface;
use WTG\RestClient\Model\Rest\ProductResponse;

/**
 * GetProduct interface.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface GetProductInterface extends ResponseInterface
{
    /**
     * @return ProductResponse
     */
    public function getProduct(): ProductResponse;

    /**
     * @return array
     */
    public function getRawProduct(): array;
}

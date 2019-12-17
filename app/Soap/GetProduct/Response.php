<?php

declare(strict_types=1);

namespace WTG\Soap\GetProduct;

use WTG\Soap\AbstractResponse;

/**
 * GetProduct response.
 *
 * @package     WTG\Soap
 * @subpackage  GetProduct
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    /**
     * @var Response\Product
     */
    public $product;
}

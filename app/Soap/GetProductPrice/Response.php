<?php

namespace WTG\Soap\GetProductPrice;

use WTG\Soap\AbstractResponse;

/**
 * GetProductPrice response.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPrice
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    /**
     * @var Response\Product
     */
    public $product;
}
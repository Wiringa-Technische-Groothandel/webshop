<?php

declare(strict_types=1);

namespace WTG\Soap\GetProducts;

use WTG\Soap\AbstractResponse;

/**
 * GetProducts response.
 *
 * @package     WTG\Soap
 * @subpackage  GetProducts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    /**
     * @var Response\Product[]
     */
    public $products = [];
}
<?php

namespace WTG\Soap\GetProductPricesAndStocks;

use WTG\Soap\AbstractResponse;

/**
 * GetProductPricesAndStocks response.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPricesAndStocks
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    /**
     * @var Response\Product[]
     */
    public $products = [];
}
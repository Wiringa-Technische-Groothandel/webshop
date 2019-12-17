<?php

declare(strict_types=1);

namespace WTG\Soap\GetProductPricesAndStocks\Request;

/**
 * GetProductPricesAndStocks product request.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPricesAndStocks\Request
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Product
{
    /**
     * @var string
     */
    public $productId;

    /**
     * @var string
     */
    public $unitId;

    /**
     * @var float
     */
    public $qtyPrice = 1.0;
}

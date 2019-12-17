<?php

declare(strict_types=1);

namespace WTG\Soap\GetProductPricesAndStocks\Response;

/**
 * GetProductPricesAndStocks product response.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPricesAndStocks\Response
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Product
{
    /**
     * @var string
     */
    public $sku;

    /**
     * @var string
     */
    public $sales_unit;

    /**
     * @var float
     */
    public $quantity;

    /**
     * @var float
     */
    public $gross_price;

    /**
     * @var float
     */
    public $net_price;

    /**
     * @var float
     */
    public $discount;

    /**
     * @var float
     */
    public $price_per;

    /**
     * @var string
     */
    public $price_unit;

    /**
     * @var float
     */
    public $stock;

    /**
     * @var int
     */
    public $refactor;

    /**
     * @var string
     */
    public $price_per_string;

    /**
     * @var string
     */
    public $stock_string;

    /**
     * @var bool
     */
    public $action = false;
}

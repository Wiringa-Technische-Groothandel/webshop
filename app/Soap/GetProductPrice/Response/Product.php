<?php

declare(strict_types=1);

namespace WTG\Soap\GetProductPrice\Response;

/**
 * GetProductPrice product response.
 *
 * @package     WTG\Soap
 * @subpackage  GetProductPrice\Response
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
     * @var string
     */
    public $discountType;

    /**
     * @var float
     */
    public $price_per;

    /**
     * @var string
     */
    public $price_per_string;

    /**
     * @var string
     */
    public $price_unit;

    /**
     * @var int
     */
    public $refactor;

    /**
     * @var bool
     */
    public $action = false;
}

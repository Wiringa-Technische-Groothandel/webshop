<?php

declare(strict_types=1);

namespace WTG\Soap\GetProducts\Response;

use Carbon\Carbon;

/**
 * GetProducts product response.
 *
 * @package     WTG\Soap
 * @subpackage  GetProducts\Response
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
    public $group;

    /**
     * @var Carbon
     */
    public $deleted_at;

    /**
     * @var string
     */
    public $sales_unit;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $ean;

    /**
     * @var float
     */
    public $vat;

    /**
     * @var string
     */
    public $packing_unit;

    /**
     * @var bool
     */
    public $discontinued;

    /**
     * @var bool
     */
    public $blocked;

    /**
     * @var bool
     */
    public $inactive;

    /**
     * Weight in KG.
     *
     * @var string
     */
    public $weight;

    /**
     * Length in CM.
     *
     * @var string
     */
    public $length;

    /**
     * Width in CM.
     *
     * @var string
     */
    public $width;

    /**
     * Height in CM.
     *
     * @var string
     */
    public $height;

    /**
     * @var string
     */
    public $brand;

    /**
     * @var string
     */
    public $series;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $related;

    /**
     * Extra keywords for searching.
     *
     * @var string
     */
    public $keywords;

    /**
     * @var bool
     */
    public $webshop;

    /**
     * @var string
     */
    public $stock_display;

    /**
     * @var string
     */
    public $supplier_code;
}
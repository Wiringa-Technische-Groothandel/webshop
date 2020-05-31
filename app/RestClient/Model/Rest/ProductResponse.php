<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest;

/**
 * Product response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProductResponse
{
    /**
     * @var string
     */
    public string $erpId;

    /**
     * @var string
     */
    public string $sku;

    /**
     * @var string
     */
    public string $group;

    /**
     * @var string
     */
    public string $salesUnit;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var null|string
     */
    public ?string $description = null;

    /**
     * @var string
     */
    public string $ean;

    /**
     * @var string
     */
    public string $vat;

    /**
     * @var string
     */
    public string $packingUnit;

    /**
     * @var bool
     */
    public bool $discontinued;

    /**
     * @var bool
     */
    public bool $blocked;

    /**
     * @var bool
     */
    public bool $inactive;

    /**
     * @var float
     */
    public float $weight;

    /**
     * @var float
     */
    public float $length;

    /**
     * @var float
     */
    public float $width;

    /**
     * @var float
     */
    public float $height;

    /**
     * @var string
     */
    public string $brand;

    /**
     * @var string
     */
    public string $series;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $related;

    /**
     * @var string
     */
    public string $keywords;

    /**
     * @var bool
     */
    public bool $isWeb;

    /**
     * @var string
     */
    public string $stockDisplay = 'S';

    /**
     * @var string
     */
    public string $supplierCode;

    /**
     * @var float
     */
    public float $minimalPurchase = 1.0;
}

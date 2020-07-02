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
    public string $erpId;
    public string $sku;
    public string $group;
    public string $salesUnit;
    public string $name;
    public ?string $description = null;
    public string $ean;
    public string $vat;
    public string $packingUnit;
    public bool $discontinued;
    public bool $blocked;
    public bool $inactive;
    public float $weight;
    public float $length;
    public float $width;
    public float $height;
    public string $brand;
    public string $series;
    public string $type;
    public ?string $related = null;
    public string $keywords;
    public bool $isWeb;
    public string $stockDisplay = 'S';
    public string $supplierCode;
    public float $minimalPurchase = 1.0;
}

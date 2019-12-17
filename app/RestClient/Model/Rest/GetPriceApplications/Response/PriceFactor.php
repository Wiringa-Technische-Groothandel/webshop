<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetPriceApplications\Response;

/**
 * Price factor response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PriceFactor
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
    public string $unitCode;

    /**
     * @var float
     */
    public float $pricePer;

    /**
     * @var string
     */
    public string $priceUnit;

    /**
     * @var float
     */
    public float $priceUnitFactor;

    /**
     * @var string
     */
    public string $scaleUnit;
}

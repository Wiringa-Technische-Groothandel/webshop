<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductPrices\Response;

/**
 * Price response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Price
{
    public string $sku;
    public string $scaleUnit;
    public string $priceUnit;

    public bool $isAction;

    public float $discount;
    public float $netPrice;
    public float $netPricePerUnit;
    public float $netTotal;
    public float $grossPrice;
    public float $pricePer;
    public float $priceFactor;
}

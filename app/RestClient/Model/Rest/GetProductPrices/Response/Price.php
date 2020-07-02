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
    public float $discount;
    public bool $actionPrice;
    public float $netPrice;
    public float $grossPrice;
    public float $pricePer;
    public float $priceFactor;
}

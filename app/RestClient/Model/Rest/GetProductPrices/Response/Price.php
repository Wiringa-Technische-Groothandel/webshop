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
    /**
     * @var string
     */
    public string $sku;

    /**
     * @var float
     */
    public float $discount;

    /**
     * @var bool
     */
    public bool $actionPrice;

    /**
     * @var float
     */
    public float $netPrice;

    /**
     * @var float
     */
    public float $grossPrice;
}

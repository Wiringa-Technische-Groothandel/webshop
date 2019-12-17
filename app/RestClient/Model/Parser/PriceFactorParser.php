<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Parser;

use WTG\RestClient\Model\Rest\GetPriceApplications\Response\PriceFactor;

/**
 * Products parser.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PriceFactorParser
{
    /**
     * Parse a response product.
     *
     * @param array $item
     * @return PriceFactor
     */
    public function parse(array $item): PriceFactor
    {
        $priceFactor = new PriceFactor();
        $priceFactor->sku = $item['productIdentifier']['productCode'];
        $priceFactor->erpId = $item['id'];
        $priceFactor->unitCode = $item['unitCode'];
        $priceFactor->pricePer = (float)$item['pricePer'];
        $priceFactor->priceUnit = $item['priceUnit'];
        $priceFactor->priceUnitFactor = (float)$item['priceUnitFactor'];
        $priceFactor->scaleUnit = $item['scaleUnit'];

        return $priceFactor;
    }
}

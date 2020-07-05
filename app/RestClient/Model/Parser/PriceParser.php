<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Parser;

use WTG\RestClient\Model\Rest\GetProductPrices\Response\Price;

/**
 * Price parser.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PriceParser
{
    /**
     * Parse a response product.
     *
     * @param array $item
     * @return Price
     */
    public function parse(array $item): Price
    {
        $price = new Price();
        $price->sku = $item['productIdentifier']['productCode'];
        $price->netPrice = $item['nettPrice'];
        $price->netPricePerUnit = $item['nettAmountPerUnit'];
        $price->netTotal = $item['nettAmountTotal'];
        $price->grossPrice = $item['price'];
        $price->discount = $item['discount'];
        $price->actionPrice = $item['actionPrice'];
        $price->pricePer = $item['pricePerUnit'];
        $price->priceFactor = $item['conversionFactor'];
        $price->scaleUnit = unit_to_str($item['unitCode'], false);
        $price->priceUnit = unit_to_str($item['priceUnit'], false);

        return $price;
    }
}

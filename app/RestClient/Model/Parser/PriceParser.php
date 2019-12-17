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
        $price->grossPrice = $item['price'];
        $price->discount = $item['discount'];
        $price->actionPrice = $item['actionPrice'] || ($item['nettPrice'] === $item['price']);

        return $price;
    }
}

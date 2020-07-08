<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Parser;

use WTG\RestClient\Model\Rest\GetProductStocks\Response\Stock;

/**
 * Stock parser.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class StockParser
{
    /**
     * Parse a response product.
     *
     * @param array $item
     * @return Stock
     */
    public function parse(array $item): Stock
    {
        $stock = new Stock();
        $stock->sku = $item['productIdentifier']['productCode'];
        $stock->actualStock = $item['actualStock'];
        $stock->actualStockBasedOnComponents = $item['actualStockBasedOnComponents'];
        $stock->availableStock = $item['availableStock'];
        $stock->availableStockBasedOnComponents = $item['availableStockBasedOnComponents'];
        $stock->availableStockForSales = $item['availableStockForSales'];
        $stock->availableStockNoPurch = $item['availableStockNoPurch'];
        $stock->blockedStock = $item['blockedStock'];
        $stock->freeStock = $item['freeStock'];
        $stock->freeStockNoPurch = $item['freeStockNoPurch'];
        $stock->reservedStock = $item['reservedStock'];

        $stock->displayStock = $item['availableStockBasedOnComponents'] ?: $item['availableStock'];

        return $stock;
    }
}

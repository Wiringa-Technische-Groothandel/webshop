<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductStocks\Response;

/**
 * Stock response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Stock
{
    public string $sku;

    public float $actualStock;
    public float $actualStockBasedOnComponents;
    public float $availableStock;
    public float $availableStockBasedOnComponents;
    public float $availableStockForSales;
    public float $availableStockNoPurch;
    public float $blockedStock;
    public float $freeStock;
    public float $freeStockNoPurch;
    public float $reservedStock;
    public float $displayStock;
}

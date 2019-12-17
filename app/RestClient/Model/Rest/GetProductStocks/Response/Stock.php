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
    /**
     * @var string
     */
    public string $sku;

    /**
     * @var float
     */
    public float $actualStock;

    /**
     * @var float
     */
    public float $actualStockBasedOnComponents;

    /**
     * @var float
     */
    public float $availableStock;

    /**
     * @var float
     */
    public float $availableStockBasedOnComponents;

    /**
     * @var float
     */
    public float $availableStockForSales;

    /**
     * @var float
     */
    public float $availableStockNoPurch;

    /**
     * @var float
     */
    public float $blockedStock;

    /**
     * @var float
     */
    public float $freeStock;

    /**
     * @var float
     */
    public float $freeStockNoPurch;

    /**
     * @var float
     */
    public float $reservedStock;
}

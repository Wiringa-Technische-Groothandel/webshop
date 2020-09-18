<?php

declare(strict_types=1);

namespace WTG\GraphQL\Buffers;

use Illuminate\Contracts\Container\BindingResolutionException;
use WTG\Managers\StockManager;
use WTG\RestClient\Model\Rest\GetProductStocks\Response\Stock;

/**
 * Stock buffer.
 *
 * @package     WTG\GraphQL\Buffers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class StockBuffer extends AbstractBuffer
{
    protected static array $items = [];
    protected static array $data = [];
    protected static bool $loaded = false;

    /**
     * Load the data for the buffered items.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public static function loadBuffered(): void
    {
        if (self::$loaded) {
            return;
        }

        /** @var StockManager $stockManager */
        $stockManager = app(StockManager::class);

        $prices = $stockManager->fetchStocks(
            collect(self::$items)
        );

        $prices->each(function (Stock $stock) {
            self::$data[$stock->sku] = $stock;
        });

        self::$loaded = true;
    }
}

<?php declare(strict_types=1);

namespace WTG\Services\Stock;

use WTG\Repositories\ProductRepository;

class Service
{
    const STATUS_OUT_OF_STOCK = 'out-of-stock';
    const STATUS_LOW_STOCK = 'low-stock';
    const STATUS_NORMAL_STOCK = 'normal-stock';

    public function getStatusForProduct(string $sku, float $stock): string
    {
        return self::STATUS_NORMAL_STOCK;
    }
}
<?php

declare(strict_types=1);

namespace WTG\GraphQL\Buffers;

use Illuminate\Contracts\Container\BindingResolutionException;
use WTG\Managers\PriceManager;
use WTG\Models\Customer;
use WTG\Models\Product;
use WTG\RestClient\Model\Rest\GetProductPrices\Response\Price;

/**
 * Abstract buffer.
 *
 * @package     WTG\GraphQL\Buffers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class AbstractBuffer
{
    /**
     * Add an item to the buffer.
     *
     * @param mixed $item
     * @return void
     */
    public static function add($item): void
    {
        static::$items[] = $item;
    }

    /**
     * Load the data for the buffered items.
     *
     * @return void
     * @throws BindingResolutionException
     */
    abstract public static function loadBuffered(): void;

    /**
     * Get the loaded data for an item.
     *
     * @param string $key
     * @return object|null
     */
    public static function get(string $key): ?object
    {
        return static::$data[$key] ?? null;
    }
}

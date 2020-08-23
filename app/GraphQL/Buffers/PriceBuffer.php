<?php

declare(strict_types=1);

namespace WTG\GraphQL\Buffers;

use Illuminate\Contracts\Container\BindingResolutionException;
use WTG\Managers\PriceManager;
use WTG\Models\Customer;
use WTG\RestClient\Model\Rest\GetProductPrices\Response\Price;

/**
 * Price buffer.
 *
 * @package     WTG\GraphQL\Buffers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PriceBuffer extends AbstractBuffer
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

        /** @var PriceManager $priceManager */
        $priceManager = app(PriceManager::class);
        /** @var Customer $customer */
        $customer = auth()->user();

        $collection = collect(self::$items)->unique('sku');

        $prices = $priceManager->fetchPrices(
            $customer->getCompany()->getCustomerNumber(),
            $collection
        );

        $prices->each(
            function (Price $price) {
                self::$data[$price->sku] = $price;
            }
        );

        self::$loaded = true;
    }
}

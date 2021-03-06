<?php

declare(strict_types=1);

namespace WTG\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Discount model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Discount extends Model
{
    public const IMPORTANCE_GENERIC = 10;
    public const IMPORTANCE_GROUP = 20;
    public const IMPORTANCE_PRODUCT = 30;
    public const IMPORTANCE_CUSTOMER = 40;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the discounts/a discount.
     *
     * @param int $companyId
     * @param Product $product
     * @return float
     */
    public static function getDiscountForProduct($companyId, Product $product)
    {
        $discounts = (new static())->findByCompany($companyId);

        return (float)
        $discounts->has($product->getAttribute('sku')) ?
            $discounts->get($product->getAttribute('sku')) :
            $discounts->get($product->getAttribute('group'));
    }

    /**
     * Get all the discounts for a company.
     *
     * @param Company|int $companyId
     * @return Collection
     */
    public function findByCompany($companyId)
    {
        if ($companyId instanceof Company) {
            $companyId = $companyId->getAttribute('id');
        }

        if (Cache::has('discounts.company.' . $companyId)) {
            return Cache::get('discounts.company.' . $companyId);
        }

        $discounts = collect([]);

        // Add the default discounts
        self::where('importance', Discount::IMPORTANCE_GENERIC)
            ->whereNotIn(
                'product',
                function ($query) use ($companyId) {
                    $query
                        ->select('product')
                        ->from('discounts')
                        ->where('importance', self::IMPORTANCE_GROUP)
                        ->where('company_id', $companyId);
                }
            )
            ->get(['discount', 'product'])
            ->each(
                function (Discount $item) use (&$discounts) {
                    $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
                }
            );

        self::where('importance', Discount::IMPORTANCE_GROUP)
            ->where('company_id', $companyId)
            ->get(['discount', 'product'])
            ->each(
                function (Discount $item) use (&$discounts) {
                    $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
                }
            );

        self::where('importance', Discount::IMPORTANCE_PRODUCT)
            ->where('company_id', $companyId)
            ->get(['discount', 'product'])
            ->each(
                function (Discount $item) use (&$discounts) {
                    $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
                }
            );

        self::where('importance', Discount::IMPORTANCE_CUSTOMER)
            ->where('company_id', $companyId)
            ->get(['discount', 'product'])
            ->each(
                function (Discount $item) use (&$discounts) {
                    $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
                }
            );

        Cache::put('discounts.company.' . $companyId, $discounts, 60 * 60 * 24); // Cache the discounts for a day

        return $discounts;
    }
}

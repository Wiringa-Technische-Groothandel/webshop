<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Discount model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Discount extends Model
{
    const IMPORTANCE_GENERIC    = 10;
    const IMPORTANCE_GROUP      = 20;
    const IMPORTANCE_PRODUCT    = 30;
    const IMPORTANCE_CUSTOMER   = 40;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the discounts/a discount.
     *
     * @param  int  $companyId
     * @param  Product  $product
     * @return float
     */
    public static function getDiscountForProduct($companyId, Product $product)
    {
        $discounts = (new static)->findByCompany($companyId);

        return (float)
            $discounts->has($product->getAttribute('sku')) ?
            $discounts->get($product->getAttribute('sku')) :
            $discounts->get($product->getAttribute('group'));
    }

    /**
     * Get all the discounts for a company.
     *
     * @param  Company|int  $companyId
     * @return \Illuminate\Support\Collection
     */
    public function findByCompany($companyId)
    {
        if ($companyId instanceof Company) {
            $companyId = $companyId->getAttribute('id');
        }

        if (\Cache::has('discounts.company.'.$companyId)) {
            return \Cache::get('discounts.company.'.$companyId);
        }

        $discounts = collect([]);

        // Add the default discounts
        self::where('importance', Discount::IMPORTANCE_GENERIC)
            ->whereNotIn('product', function ($query) use ($companyId) {
                $query
                    ->select('product')
                    ->from('discounts')
                    ->where('importance', self::IMPORTANCE_GROUP)
                    ->where('company_id', $companyId);
            })
            ->get(['discount', 'product'])
            ->each(function (Discount $item) use (&$discounts) {
                $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
            });

        self::where('importance', Discount::IMPORTANCE_GROUP)
            ->where('company_id', $companyId)
            ->get(['discount', 'product'])
            ->each(function (Discount $item) use (&$discounts) {
                $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
            });

        self::where('importance', Discount::IMPORTANCE_PRODUCT)
            ->where('company_id', $companyId)
            ->get(['discount', 'product'])
            ->each(function (Discount $item) use (&$discounts) {
                $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
            });

        self::where('importance', Discount::IMPORTANCE_CUSTOMER)
            ->where('company_id', $companyId)
            ->get(['discount', 'product'])
            ->each(function (Discount $item) use (&$discounts) {
                $discounts->put($item->getAttribute('product'), $item->getAttribute('discount'));
            });

        \Cache::put('discounts.company.'.$companyId, $discounts, 60 * 24); // Cache the discounts for a day

        return $discounts;
    }
}
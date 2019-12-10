<?php

declare(strict_types=1);

namespace WTG\Converters;

use WTG\Models\Product;
use WTG\Models\Customer;
use WTG\Models\Favorite;
use Illuminate\Database\Eloquent\Model;

/**
 * Favorites table converter.
 *
 * @package     WTG\Converters
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FavoritesTableConverter extends AbstractTableConverter
{
    /**
     * @var array
     */
    protected $csvFields = [
        'id',
        'username',
        'company_id',
        'email',
        'isAdmin',
        'manager',
        'password',
        'favorites',
        'cart',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * Create a new entity.
     *
     * @param  array  $data
     * @return Model|null
     */
    public function createModel(array $data): ?Model
    {
        try {
            $favorites = unserialize($data['favorites']);
        } catch (\Exception $e) {
            \Log::warning("[Favorites table converter] Invalid data in favorites column for customer " . $data['username']);

            return null;
        }

        if (empty($favorites)) {
            return null;
        }

        $customer = Customer::whereHas('company', function ($query) use ($data) {
            $query->where('customer_number', $data['company_id']);
        })->where('username', $data['username'])->first();

        if (!$customer) {
            \Log::warning(sprintf("[Favorites table converter] Skipped customer %s for company %s. Reason: Customer was not found in the database.", $data['username'], $data['company_id']));

            return null;
        }

        foreach ($favorites as $favorite) {
            $product = Product::where('sku', $favorite)->first();

            if (!$product) {
                \Log::warning(sprintf("[Favorites table converter] Removed product %s from customer favorites. Reason: Product was not found in the database.", $favorite));

                continue;
            }

            $favoriteModel = new Favorite;

            $favoriteModel->setAttribute('customer_id', $customer->getAttribute('id'));
            $favoriteModel->setAttribute('product_id', $product->getAttribute('id'));

            $favoriteModel->save();
        }

        return null;
    }
}
<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\FavoriteContract;
use WTG\Contracts\Models\ProductContract;

/**
 * Favorite model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Favorite extends Model implements FavoriteContract
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Customer relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Product relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product.
     *
     * @return null|ProductContract
     */
    public function getProduct(): ?ProductContract
    {
        return $this->getAttribute('product');
    }

    /**
     * Get the customer.
     *
     * @return null|CustomerContract
     */
    public function getCustomer(): ?CustomerContract
    {
        return $this->getAttribute('customer');
    }
}
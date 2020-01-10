<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\Model\Product;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\FavoriteContract;

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
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Product relation.
     *
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product.
     *
     * @return null|ProductInterface
     */
    public function getProduct(): ?ProductInterface
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

<?php

namespace WTG\Models;

use WTG\Contracts\ContactContract;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\CustomerContract;

/**
 * Contact model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Contact extends Model implements ContactContract
{
    /**
     * Company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the identifier
     *
     * @param null|string $id
     * @return string
     */
    public function identifier(?string $id = null): string
    {
        return $this->getKey();
    }

    /**
     * Get the customer.
     *
     * @return CustomerContract
     */
    public function getCustomer(): CustomerContract
    {
        return $this->getAttribute('customer');
    }
}
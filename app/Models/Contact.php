<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\ContactContract;
use WTG\Contracts\Models\CustomerContract;

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
     * Address relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class)
            ->where('company_id', $this->getCustomer()->getCompany()->getId());
    }

    /**
     * Get the identifier
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
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

    /**
     * Set the contact email.
     *
     * @param  string  $email
     * @return ContactContract
     */
    public function setContactEmail(string $email): ContactContract
    {
        return $this->setAttribute('contact_email', $email);
    }

    /**
     * Get the contact email.
     *
     * @return null|string
     */
    public function getContactEmail(): ?string
    {
        return $this->getAttribute('contact_email');
    }

    /**
     * Set the order email.
     *
     * @param  string  $email
     * @return ContactContract
     */
    public function setOrderEmail(string $email): ContactContract
    {
        return $this->setAttribute('order_email', $email);
    }

    /**
     * Get the order email.
     *
     * @return null|string
     */
    public function getOrderEmail(): ?string
    {
        return $this->getAttribute('order_email');
    }

    /**
     * Set the default address.
     *
     * @param  int  $addressId
     * @return ContactContract
     */
    public function setDefaultAddress(int $addressId): ContactContract
    {
        $this->address()->associate($addressId);
        return $this;
    }

    /**
     * Get the default address.
     *
     * @return null|AddressContract
     */
    public function getDefaultAddress(): ?AddressContract
    {
        return $this->getAttribute('address');
    }
}
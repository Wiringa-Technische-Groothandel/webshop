<?php

namespace WTG\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\CompanyContract;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Company model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Company extends Model implements CompanyContract
{
    use SoftDeletes;

    /**
     * Customers relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Address relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Order relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the attached customers.
     *
     * @return Collection
     */
    public function getCustomers(): Collection
    {
        return $this->getAttribute('customers');
    }

    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Get or set the name.
     *
     * @param  string  $name
     * @return CompanyContract
     */
    public function setName(string $name): CompanyContract
    {
        return $this->setAttribute('name', $name);
    }

    /**
     * Get or set the name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }

    /**
     * Set the customer number.
     *
     * @param  string  $customerNumber
     * @return CompanyContract
     */
    public function setCustomerNumber(string $customerNumber): CompanyContract
    {
        return $this->setAttribute('customer_number', $customerNumber);
    }

    /**
     * Get the customer number.
     *
     * @return null|string
     */
    public function getCustomerNumber(): ?string
    {
        return $this->getAttribute('customer_number');
    }

    /**
     * Get the addresses.
     *
     * @return Collection
     */
    public function getAddresses(): Collection
    {
        return $this->getAttribute('addresses');
    }

    /**
     * Change the default address.
     *
     * @param  int|Address  $address
     * @return bool
     */
//    public function setDefaultAddress($address): bool
//    {
//        if (! $address instanceof Address) {
//            $address = Address::where('company_id', $this->getAttribute('id'))
//                ->where('id', $address)
//                ->first();
//
//            if ($address === null) {
//                return false;
//            }
//        }
//
//        if ($address->getAttribute('company_id') !== $this->getAttribute('id')) {
//            return false;
//        }
//
//        $this->addresses()->where(Address::COLUMN_IS_DEFAULT, true)->update([
//            Address::COLUMN_IS_DEFAULT => false
//        ]);
//
//        $address->setAttribute(Address::COLUMN_IS_DEFAULT, true);
//
//        return $address->save();
//    }
}
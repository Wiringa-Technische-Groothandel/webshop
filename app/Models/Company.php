<?php

namespace WTG\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\AddressContract;
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
        return $this->hasMany(Customer::class)->with('contact');
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
     * @return Collection|AddressContract[]
     */
    public function getAddresses(): Collection
    {
        return $this->getAttribute('addresses');
    }

    /**
     * Set the street.
     *
     * @param  string  $street
     * @return CompanyContract
     */
    public function setStreet(string $street): CompanyContract
    {
        return $this->setAttribute('street', $street);
    }

    /**
     * Get the street.
     *
     * @return null|string
     */
    public function getStreet(): ?string
    {
        return $this->getAttribute('street');
    }

    /**
     * Set the postcode.
     *
     * @param  null|string  $postcode
     * @return CompanyContract
     */
    public function setPostcode(string $postcode): CompanyContract
    {
        return $this->setAttribute('postcode', $postcode);
    }

    /**
     * Get the postcode.
     *
     * @return null|string
     */
    public function getPostcode(): ?string
    {
        return $this->getAttribute('postcode');
    }

    /**
     * Set the city.
     *
     * @param  null|string  $city
     * @return CompanyContract
     */
    public function setCity(string $city): CompanyContract
    {
        return $this->setAttribute('city', $city);
    }

    /**
     * Get the city.
     *
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->getAttribute('city');
    }

    /**
     * Set the active state.
     *
     * @param  bool  $active
     * @return CompanyContract
     */
    public function setActive(bool $active): CompanyContract
    {
        return $this->setAttribute('active', $active);
    }

    /**
     * Get the active state.
     *
     * @return null|bool
     */
    public function getActive(): ?bool
    {
        return $this->getAttribute('active');
    }
}
<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CompanyContract;

/**
 * Address model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Address extends Model implements AddressContract
{
    public const DEFAULT_ID = 0;

    /**
     * Get the identifier.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the company.
     *
     * @param CompanyContract $company
     * @return AddressContract
     */
    public function setCompany(CompanyContract $company): AddressContract
    {
        $this->company()->associate($company);

        return $this;
    }

    /**
     * Related company model.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the company.
     *
     * @return null|CompanyContract
     */
    public function getCompany(): ?CompanyContract
    {
        return $this->getAttribute('company');
    }

    /**
     * Set the name.
     *
     * @param string $name
     * @return AddressContract
     */
    public function setName(string $name): AddressContract
    {
        return $this->setAttribute('name', $name);
    }

    /**
     * Get the name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }

    /**
     * Set the street.
     *
     * @param string $street
     * @return AddressContract
     */
    public function setStreet(string $street): AddressContract
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
     * @param null|string $postcode
     * @return AddressContract
     */
    public function setPostcode(string $postcode): AddressContract
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
     * @param null|string $city
     * @return AddressContract
     */
    public function setCity(string $city): AddressContract
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
     * Set the phone.
     *
     * @param null|string $phone
     * @return AddressContract
     */
    public function setPhone(?string $phone = null): AddressContract
    {
        return $this->setAttribute('phone', $phone);
    }

    /**
     * Get the phone.
     *
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->getAttribute('phone');
    }

    /**
     * Set the mobile.
     *
     * @param null|string $mobile
     * @return AddressContract
     */
    public function setMobile(?string $mobile = null): AddressContract
    {
        return $this->setAttribute('mobile', $mobile);
    }

    /**
     * Get the mobile.
     *
     * @return null|string
     */
    public function getMobile(): ?string
    {
        return $this->getAttribute('mobile');
    }
}

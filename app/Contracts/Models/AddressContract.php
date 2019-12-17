<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

/**
 * Address contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AddressContract
{
    /**
     * Get the identifier.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Set the company.
     *
     * @param CompanyContract $company
     * @return AddressContract
     */
    public function setCompany(CompanyContract $company): AddressContract;

    /**
     * Get the company.
     *
     * @return null|CompanyContract
     */
    public function getCompany(): ?CompanyContract;

    /**
     * Set the name.
     *
     * @param string $name
     * @return AddressContract
     */
    public function setName(string $name): AddressContract;

    /**
     * Get the name.
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set the street.
     *
     * @param string $street
     * @return AddressContract
     */
    public function setStreet(string $street): AddressContract;

    /**
     * Get the street.
     *
     * @return null|string
     */
    public function getStreet(): ?string;

    /**
     * Set the postcode.
     *
     * @param null|string $postcode
     * @return AddressContract
     */
    public function setPostcode(string $postcode): AddressContract;

    /**
     * Get the postcode.
     *
     * @return null|string
     */
    public function getPostcode(): ?string;

    /**
     * Set the city.
     *
     * @param null|string $city
     * @return AddressContract
     */
    public function setCity(string $city): AddressContract;

    /**
     * Get the city.
     *
     * @return null|string
     */
    public function getCity(): ?string;

    /**
     * Set the phone.
     *
     * @param null|string $phone
     * @return AddressContract
     */
    public function setPhone(?string $phone = null): AddressContract;

    /**
     * Get the phone.
     *
     * @return null|string
     */
    public function getPhone(): ?string;

    /**
     * Set the mobile.
     *
     * @param null|string $mobile
     * @return AddressContract
     */
    public function setMobile(?string $mobile = null): AddressContract;

    /**
     * Get the mobile.
     *
     * @return null|string
     */
    public function getMobile(): ?string;
}

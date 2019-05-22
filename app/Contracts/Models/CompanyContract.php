<?php

namespace WTG\Contracts\Models;

use Illuminate\Support\Collection;

/**
 * Company contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CompanyContract
{
    /**
     * Get the attached customers.
     *
     * @return Collection
     */
    public function getCustomers(): Collection;

    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Get or set the name.
     *
     * @param  string  $name
     * @return CompanyContract
     */
    public function setName(string $name): CompanyContract;

    /**
     * Get or set the name.
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set the customer number.
     *
     * @param  string  $customerNumber
     * @return CompanyContract
     */
    public function setCustomerNumber(string $customerNumber): CompanyContract;

    /**
     * Get the customer number.
     *
     * @return null|string
     */
    public function getCustomerNumber(): ?string;

    /**
     * Get the addresses.
     *
     * @return Collection|AddressContract[]
     */
    public function getAddresses(): Collection;

    /**
     * Set the street.
     *
     * @param  string  $street
     * @return CompanyContract
     */
    public function setStreet(string $street): CompanyContract;

    /**
     * Get the street.
     *
     * @return null|string
     */
    public function getStreet(): ?string;

    /**
     * Set the postcode.
     *
     * @param  null|string  $postcode
     * @return CompanyContract
     */
    public function setPostcode(string $postcode): CompanyContract;

    /**
     * Get the postcode.
     *
     * @return null|string
     */
    public function getPostcode(): ?string;

    /**
     * Set the city.
     *
     * @param  null|string  $city
     * @return CompanyContract
     */
    public function setCity(string $city): CompanyContract;

    /**
     * Get the city.
     *
     * @return null|string
     */
    public function getCity(): ?string;

    /**
     * Set the active state.
     *
     * @param  bool  $active
     * @return CompanyContract
     */
    public function setActive(bool $active): CompanyContract;

    /**
     * Get the active state.
     *
     * @return null|bool
     */
    public function getActive(): ?bool;
}
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
     * @return Collection
     */
    public function getAddresses(): Collection;
}
<?php

declare(strict_types=1);

namespace WTG\Contracts\Services\Account;

use Illuminate\Support\Collection;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CustomerContract;

/**
 * Address service contract.
 *
 * @package     WTG
 * @subpackage  Services\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AddressManagerContract
{
    /**
     * Get all available addresses for a customer.
     *
     * @param CustomerContract $customer
     * @param bool $withDefault
     * @return Collection
     */
    public function getAddressesForCustomer(CustomerContract $customer, bool $withDefault = true): Collection;

    /**
     * Get an address for a customer by id.
     *
     * @param CustomerContract $customer
     * @param int $addressId
     * @return null|AddressContract
     */
    public function getAddressForCustomerById(CustomerContract $customer, int $addressId): ?AddressContract;

    /**
     * Create a new address.
     *
     * @param CustomerContract $customer
     * @param string $name
     * @param string $street
     * @param string $postcode
     * @param string $city
     * @param null|string $phone
     * @param null|string $mobile
     * @param bool $isDefault
     * @return bool
     */
    public function createForCustomer(
        CustomerContract $customer,
        string $name,
        string $street,
        string $postcode,
        string $city,
        ?string $phone = null,
        ?string $mobile = null,
        bool $isDefault = false
    ): bool;

    /**
     * Delete an address for a customer.
     *
     * @param CustomerContract $customer
     * @param string $addressId
     * @return bool
     */
    public function deleteForCustomer(CustomerContract $customer, string $addressId): bool;

    /**
     * Set the default address for a customer.
     *
     * @param CustomerContract $customer
     * @param int $addressId
     * @return bool
     */
    public function setDefaultForCustomer(CustomerContract $customer, int $addressId): bool;

    /**
     * Get the default address for a customer.
     *
     * @param CustomerContract $customer
     * @return null|AddressContract
     */
    public function getDefaultAddressForCustomer(CustomerContract $customer): ?AddressContract;

    /**
     * Get the default address id for a customer.
     *
     * @param CustomerContract $customer
     * @return null|int
     */
    public function getDefaultAddressIdForCustomer(CustomerContract $customer): ?int;

    /**
     * Get the pickup address.
     *
     * @return AddressContract
     */
    public function getPickupAddress(): AddressContract;
}

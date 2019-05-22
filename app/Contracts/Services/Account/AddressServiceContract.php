<?php

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
interface AddressServiceContract
{
    /**
     * Get all available addresses for a customer.
     *
     * @param  CustomerContract $customer
     * @param  bool  $withDefault
     * @return Collection
     */
    public function getAddressesForCustomer(CustomerContract $customer, $withDefault = true): Collection;

    /**
     * Get an address for a customer by id.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return null|AddressContract
     */
    public function getAddressForCustomerById(CustomerContract $customer, string $addressId): ?AddressContract;

    /**
     * Create a new address.
     *
     * @param  CustomerContract  $customer
     * @param  string  $name
     * @param  string  $street
     * @param  string  $postcode
     * @param  string  $city
     * @param  null|string  $phone
     * @param  null|string  $mobile
     * @return bool
     */
    public function createForCustomer(CustomerContract $customer, string $name, string $street, string $postcode,
                                      string $city, ?string $phone = null, ?string $mobile = null): bool;

    /**
     * Delete an address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     */
    public function deleteForCustomer(CustomerContract $customer, string $addressId): bool;

    /**
     * Set the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     */
    public function setDefaultForCustomer(CustomerContract $customer, string $addressId): bool;

    /**
     * Get the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDefaultAddressForCustomer(CustomerContract $customer): ?AddressContract;

    /**
     * Get the default address id for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|string
     */
    public function getDefaultAddressIdForCustomer(CustomerContract $customer): ?string;

    /**
     * Get the default shipping address.
     *
     * @return AddressContract
     */
    public function getDefaultAddress(): AddressContract;
}
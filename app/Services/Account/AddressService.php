<?php

namespace WTG\Services\Account;

use WTG\Models\Address;
use WTG\Models\Contact;
use Illuminate\Support\Collection;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CustomerContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use WTG\Contracts\Services\Account\AddressServiceContract;

/**
 * Address service.
 *
 * @package     WTG
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressService implements AddressServiceContract
{
    /**
     * Get all available addresses for a customer;
     *
     * @param  CustomerContract  $customer
     * @return Collection
     */
    public function getAddressesForCustomer(CustomerContract $customer): Collection
    {
        $company = $customer->getCompany();

        return $company->getAddresses();
    }

    /**
     * Get an address for a customer by id.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return null|AddressContract
     */
    public function getAddressForCustomerById(CustomerContract $customer, string $addressId): ?AddressContract
    {
        $addresses = $this->getAddressesForCustomer($customer);
        /** @var AddressContract $address */
        $address = $addresses->firstWhere('id', $addressId);

        return $address;
    }

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
                                      string $city, ?string $phone = null, ?string $mobile = null): bool
    {
        $address = new Address;
        $address->setCompany($customer->getCompany());
        $address->setName($name);
        $address->setStreet($street);
        $address->setPostcode($postcode);
        $address->setCity($city);
        $address->setPhone($phone);
        $address->setMobile($mobile);

        return $address->save();
    }

    /**
     * Delete an address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function deleteForCustomer(CustomerContract $customer, string $addressId): bool
    {
        /** @var Address $address */
        $address = app()->make(AddressContract::class)
            ->where('customer_id', $customer->getId())
            ->where('id', $addressId)
            ->firstOrFail();

        return $address->delete();
    }

    /**
     * Set the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @param  string  $addressId
     * @return bool
     */
    public function setDefaultForCustomer(CustomerContract $customer, string $addressId): bool
    {
        /** @var Contact $contact */
        $contact = $customer->getContact();
        $contact->setDefaultAddress($addressId);

        return $contact->save();
    }

    /**
     * Get the default address for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|AddressContract
     */
    public function getDefaultAddressForCustomer(CustomerContract $customer): ?AddressContract
    {
        return $customer->getContact()->getDefaultAddress();
    }

    /**
     * Get the default address id for a customer.
     *
     * @param  CustomerContract  $customer
     * @return null|string
     */
    public function getDefaultAddressIdForCustomer(CustomerContract $customer): ?string
    {
        $defaultAddress = $this->getDefaultAddressForCustomer($customer);

        return $defaultAddress ? $defaultAddress->getId() : null;
    }
}
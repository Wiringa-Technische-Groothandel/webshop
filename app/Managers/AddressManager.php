<?php

declare(strict_types=1);

namespace WTG\Managers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\Account\AddressManagerContract;
use WTG\Models\Address;
use WTG\Models\Contact;

/**
 * Address manager.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressManager implements AddressManagerContract
{
    /**
     * Get an address for a customer by id.
     *
     * @param CustomerContract $customer
     * @param int $addressId
     * @return null|AddressContract
     */
    public function getAddressForCustomerById(CustomerContract $customer, int $addressId): ?AddressContract
    {
        $addresses = $this->getAddressesForCustomer($customer);
        /** @var AddressContract $address */
        $address = $addresses->firstWhere('id', $addressId);

        return $address;
    }

    /**
     * Get all available addresses for a customer;
     *
     * @param CustomerContract $customer
     * @param bool $withDefault
     * @return Collection
     */
    public function getAddressesForCustomer(CustomerContract $customer, bool $withDefault = true): Collection
    {
        $company = $customer->getCompany();
        $addresses = $company->getAddresses();

        if ($withDefault) {
            $addresses->prepend($this->getPickupAddress());
        }

        return $addresses;
    }

    /**
     * Get the pickup shipping address.
     *
     * @return AddressContract
     */
    public function getPickupAddress(): AddressContract
    {
        return app(Address::class)->find(Address::DEFAULT_ID);
    }

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
    ): bool {
        try {
            /** @var Address $address */
            $address = app()->make(AddressContract::class);
            $address->setCompany($customer->getCompany());
            $address->setName($name);
            $address->setStreet($street);
            $address->setPostcode($postcode);
            $address->setCity($city);
            $address->setPhone($phone);
            $address->setMobile($mobile);

            $address->saveOrFail();

            if ($isDefault) {
                $this->setDefaultForCustomer($customer, $address->getId());
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Delete an address for a customer.
     *
     * @param CustomerContract $customer
     * @param string $addressId
     * @return bool
     * @throws ModelNotFoundException
     * @throws Exception
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
     * @param CustomerContract $customer
     * @param int $addressId
     * @return bool
     */
    public function setDefaultForCustomer(CustomerContract $customer, int $addressId): bool
    {
        /** @var Contact $contact */
        $contact = $customer->getContact();
        $contact->setDefaultAddress($addressId);

        return $contact->save();
    }

    /**
     * Get the default address id for a customer.
     *
     * @param CustomerContract $customer
     * @return null|int
     */
    public function getDefaultAddressIdForCustomer(CustomerContract $customer): ?int
    {
        $defaultAddress = $this->getDefaultAddressForCustomer($customer);

        if (!$defaultAddress) {
            return null;
        }

        return $defaultAddress->getId();
    }

    /**
     * Get the default address for a customer.
     *
     * @param CustomerContract $customer
     * @return null|AddressContract
     */
    public function getDefaultAddressForCustomer(CustomerContract $customer): ?AddressContract
    {
        $defaultAddress = $customer->getContact()->getDefaultAddress();

        if ($defaultAddress === null) {
            $defaultAddress = $this->getAddressesForCustomer($customer, false)->first();

            if ($defaultAddress) {
                $customer->getContact()->setDefaultAddress($defaultAddress->getId());
                $customer->getContact()->save();
            }
        }

        return $defaultAddress;
    }
}

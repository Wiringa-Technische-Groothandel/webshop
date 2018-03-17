<?php

namespace WTG\Contracts\Models;

/**
 * Contact contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface ContactContract
{
    /**
     * Get the identifier
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Get the customer.
     *
     * @return CustomerContract
     */
    public function getCustomer(): CustomerContract;

    /**
     * Set the contact email.
     *
     * @param  string  $email
     * @return ContactContract
     */
    public function setContactEmail(string $email): ContactContract;

    /**
     * Get the contact email.
     *
     * @return null|string
     */
    public function getContactEmail(): ?string;

    /**
     * Set the order email.
     *
     * @param  string  $email
     * @return ContactContract
     */
    public function setOrderEmail(string $email): ContactContract;

    /**
     * Get the order email.
     *
     * @return null|string
     */
    public function getOrderEmail(): ?string;

    /**
     * Set the default address.
     *
     * @param  int  $addressId
     * @return ContactContract
     */
    public function setDefaultAddress(int $addressId): ContactContract;

    /**
     * Get the default address.
     *
     * @return null|AddressContract
     */
    public function getDefaultAddress(): ?AddressContract;
}
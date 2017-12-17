<?php

namespace WTG\Contracts;

/**
 * Contact contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface ContactContract
{
    /**
     * Get or set the identifier.
     *
     * @param  null|string  $id
     * @return string
     */
    public function identifier(?string $id = null): string;

    /**
     * Get the customer.
     *
     * @return CustomerContract
     */
    public function getCustomer(): CustomerContract;
}
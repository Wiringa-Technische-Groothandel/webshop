<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

/**
 * Registration contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface RegistrationContract
{
    /**
     * @return string
     */
    public function getContactCompany(): string;

    /**
     * @return string
     */
    public function getContactName(): string;

    /**
     * @return string
     */
    public function getContactAddress(): string;

    /**
     * @return string
     */
    public function getContactCity(): string;

    /**
     * @return string
     */
    public function getContactPostcode(): string;

    /**
     * @return string
     */
    public function getContactPhoneCompany(): string;

    /**
     * @return string
     */
    public function getContactPhone(): string;

    /**
     * @return string
     */
    public function getContactEmail(): string;

    /**
     * @return null|string
     */
    public function getContactWebsite(): ?string;

    /**
     * @return string
     */
    public function getBusinessAddress(): string;

    /**
     * @return string
     */
    public function getBusinessCity(): string;

    /**
     * @return string
     */
    public function getBusinessPostcode(): string;

    /**
     * @return string
     */
    public function getBusinessPhone(): string;

    /**
     * @return string
     */
    public function getPaymentIban(): string;

    /**
     * @return string
     */
    public function getPaymentKvk(): string;

    /**
     * @return string
     */
    public function getPaymentVat(): string;

    /**
     * @return string
     */
    public function getOtherAltEmail(): string;

    /**
     * @return bool
     */
    public function getOtherOrderConfirmation(): bool;

    /**
     * @return bool
     */
    public function getOtherMailProductfile(): bool;
}
<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\RegistrationContract;

/**
 * Registration model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Registration extends Model implements RegistrationContract
{
    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @return string
     */
    public function getContactCompany(): string
    {
        return (string)$this->getAttribute('contact-company');
    }

    /**
     * @return string
     */
    public function getContactName(): string
    {
        return (string)$this->getAttribute('contact-name');
    }

    /**
     * @return string
     */
    public function getContactAddress(): string
    {
        return (string)$this->getAttribute('contact-address');
    }

    /**
     * @return string
     */
    public function getContactCity(): string
    {
        return (string)$this->getAttribute('contact-city');
    }

    /**
     * @return string
     */
    public function getContactPostcode(): string
    {
        return (string)$this->getAttribute('contact-postcode');
    }

    /**
     * @return string
     */
    public function getContactPhoneCompany(): string
    {
        return (string)$this->getAttribute('contact-phone-company');
    }

    /**
     * @return string
     */
    public function getContactPhone(): string
    {
        return (string)$this->getAttribute('contact-phone');
    }

    /**
     * @return string
     */
    public function getContactEmail(): string
    {
        return (string)$this->getAttribute('contact-email');
    }

    /**
     * @return string
     */
    public function getContactWebsite(): string
    {
        return (string)$this->getAttribute('contact-website');
    }

    /**
     * @return string
     */
    public function getBusinessAddress(): string
    {
        return (string)$this->getAttribute('business-address');
    }

    /**
     * @return string
     */
    public function getBusinessCity(): string
    {
        return (string)$this->getAttribute('business-city');
    }

    /**
     * @return string
     */
    public function getBusinessPostcode(): string
    {
        return (string)$this->getAttribute('business-postcode');
    }

    /**
     * @return string
     */
    public function getBusinessPhone(): string
    {
        return (string)$this->getAttribute('business-phone');
    }

    /**
     * @return string
     */
    public function getPaymentIban(): string
    {
        return (string)$this->getAttribute('payment-iban');
    }

    /**
     * @return string
     */
    public function getPaymentKvk(): string
    {
        return (string)$this->getAttribute('payment-kvk');
    }

    /**
     * @return string
     */
    public function getPaymentVat(): string
    {
        return (string)$this->getAttribute('payment-vat');
    }

    /**
     * @return string
     */
    public function getOtherAltEmail(): string
    {
        return (string)$this->getAttribute('other-alt-email');
    }

    /**
     * @return bool
     */
    public function getOtherOrderConfirmation(): bool
    {
        return (bool)$this->getAttribute('other-order-confirmation');
    }

    /**
     * @return bool
     */
    public function getOtherMailProductfile(): bool
    {
        return (bool)$this->getAttribute('other-mail-productfile');
    }
}

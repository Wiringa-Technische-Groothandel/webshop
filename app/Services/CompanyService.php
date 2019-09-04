<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Contracts\Container\BindingResolutionException;

use Throwable;

use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\ContactContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\CompanyServiceContract;
use WTG\Exceptions\Company\DuplicateCustomerNumberException;
use WTG\Exceptions\Company\IncompleteDataException;
use WTG\Models\Company;
use WTG\Models\Contact;
use WTG\Models\Customer;

/**
 * Admin company service.
 *
 * @package WTG\Services\Admin
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CompanyService implements CompanyServiceContract
{
    /**
     * Create a new company.
     *
     * @param array $data
     * @return CompanyContract
     * @throws DuplicateCustomerNumberException
     * @throws IncompleteDataException
     * @throws Throwable
     */
    public function createCompany(array $data): CompanyContract
    {
        $this->validateData($data);

        $duplicate = app()->make(CompanyContract::class)
                          ->where('customer_number', $data['customer-number'])
                          ->exists();

        if ($duplicate) {
            throw new DuplicateCustomerNumberException(
                __(
                    'Er bestaat reeds een debiteur met nummer :number.',
                    [
                        'number' => $data['customer-number'],
                    ]
                )
            );
        }

        /** @var Company $company */
        $company = app()->make(CompanyContract::class);
        $company->setName($data['name']);
        $company->setCustomerNumber($data['customer-number']);
        $company->setStreet($data['street']);
        $company->setCity($data['city']);
        $company->setPostcode($data['postcode']);
        $company->setActive((bool) ($data['active'] ?? false));
        $company->saveOrFail();

        $this->createDefaultCustomer($company, $data['email']);

        return $company;
    }

    /**
     * Create a default customer for a company.
     *
     * @param CompanyContract $company
     * @param string $email
     * @return CustomerContract
     * @throws BindingResolutionException
     * @throws Throwable
     */
    protected function createDefaultCustomer(CompanyContract $company, string $email): CustomerContract
    {
        $password = str_random();

        session()->flash('new-customer-password', $password);

        /** @var Customer $customer */
        $customer = app()->make(CustomerContract::class);
        $customer->setActive(true);
        $customer->setUsername($company->getCustomerNumber());
        $customer->setPassword(bcrypt($password));
        $customer->company()->associate($company);
        $customer->saveOrFail();

        $this->createCustomerContact($customer, $email);

        return $customer;
    }

    /**
     * Create a contact for a customer.
     *
     * @param CustomerContract $customer
     * @param string $email
     * @return ContactContract
     * @throws BindingResolutionException
     * @throws Throwable
     */
    protected function createCustomerContact(CustomerContract $customer, string $email): ContactContract
    {
        /** @var Contact $contact */
        $contact = app()->make(ContactContract::class);
        $contact->setContactEmail($email);
        $contact->setOrderEmail($email);
        $contact->customer()->associate($customer);
        $contact->saveOrFail();

        return $contact;
    }

    /**
     * Validate the data for creating a new company.
     *
     * @param array $data
     * @return void
     * @throws IncompleteDataException
     */
    protected function validateData(array $data): void
    {
        $errors = [];

        if (! isset($data['name'])) {
            $errors[] = __("Missing required field 'name'.");
        }

        if (! isset($data['customer-number'])) {
            $errors[] = __("Missing required field 'customer-number'.");
        }

        if (! isset($data['street'])) {
            $errors[] = __("Missing required field 'street'.");
        }

        if (! isset($data['city'])) {
            $errors[] = __("Missing required field 'city'.");
        }

        if (! isset($data['postcode'])) {
            $errors[] = __("Missing required field 'postcode'.");
        }

        if (! isset($data['email'])) {
            $errors[] = __("Missing required field 'email'.");
        }

        if ($errors) {
            throw new IncompleteDataException($errors);
        }
    }
}
<?php

declare(strict_types=1);

namespace WTG\Services;

use Exception;
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
                          ->where('customer_number', $data['customer_number'])
                          ->exists();

        if ($duplicate) {
            throw new DuplicateCustomerNumberException(
                __(
                    'Er bestaat reeds een debiteur met nummer :number.',
                    [
                        'number' => $data['customer_number'],
                    ]
                )
            );
        }

        /** @var Company $company */
        $company = app()->make(CompanyContract::class);

        $this->setCompanyData($data, $company);
        $this->createDefaultCustomer($company, $data['email']);

        return $company;
    }

    /**
     * Update a company.
     *
     * @param array $data
     * @return CompanyContract
     * @throws BindingResolutionException
     * @throws IncompleteDataException
     * @throws Throwable
     */
    public function updateCompany(array $data): CompanyContract
    {
        $this->validateData($data, true);

        /** @var Company $company */
        $company = app()->make(CompanyContract::class)
            ->where('customer_number', $data['customer_number'])
            ->first();

        if (! $company) {
            throw new Exception(
                __(
                    'Er is geen debiteur gevonden met nummer :number.',
                    [
                        'number' => $data['customer_number'],
                    ]
                )
            );
        }

        $this->setCompanyData($data, $company);

        return $company;
    }

    /**
     * Save the data on the company
     *
     * @param array $data
     * @param Company $company
     * @return void
     * @throws Throwable
     */
    protected function setCompanyData(array $data, Company $company): void
    {
        $company->setName($data['name']);
        $company->setCustomerNumber($data['customer_number']);
        $company->setStreet($data['street']);
        $company->setCity($data['city']);
        $company->setPostcode($data['postcode']);
        $company->setActive((bool) ($data['active'] ?? false));
        $company->saveOrFail();
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
        session()->flash('new-customer-password-id', $company->getId());

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
     * @param bool $update
     * @return void
     * @throws IncompleteDataException
     */
    protected function validateData(array $data, bool $update = false): void
    {
        $errors = [];

        if (! isset($data['name'])) {
            $errors[] = __("Veld 'Naam' is vereist.");
        }

        if (! isset($data['customer_number'])) {
            $errors[] = __("Veld 'Debiteurnummer' is vereist.");
        }

        if (! isset($data['street'])) {
            $errors[] = __("Veld 'Street' is vereist.");
        }

        if (! isset($data['city'])) {
            $errors[] = __("Veld 'Plaats' is vereist.");
        }

        if (! isset($data['postcode'])) {
            $errors[] = __("Veld 'Postcode' is vereist.");
        }

        if (! isset($data['email']) && !$update) {
            $errors[] = __("Veld 'E-Mail' is vereist.");
        }

        if ($errors) {
            throw new IncompleteDataException($errors);
        }
    }
}
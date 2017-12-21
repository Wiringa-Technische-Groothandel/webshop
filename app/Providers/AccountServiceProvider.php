<?php

namespace WTG\Providers;

use WTG\Models\Company;
use WTG\Models\Contact;
use WTG\Models\Customer;
use WTG\Services\AddressService;
use Illuminate\Support\ServiceProvider;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\ContactContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\AddressServiceContract;

/**
 * Customer service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AccountServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CompanyContract::class, Company::class);
        $this->app->bind(CustomerContract::class, Customer::class);
        $this->app->bind(ContactContract::class, Contact::class);
        $this->app->bind(AddressServiceContract::class, AddressService::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
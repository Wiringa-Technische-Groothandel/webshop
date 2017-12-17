<?php

namespace WTG\Providers;

use WTG\Contracts\CustomerContract;
use WTG\Models\Contact;
use WTG\Models\Customer;
use WTG\Contracts\ContactContract;
use Illuminate\Support\ServiceProvider;

/**
 * Customer service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CustomerContract::class, Customer::class);
        $this->app->bind(ContactContract::class, Contact::class);
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
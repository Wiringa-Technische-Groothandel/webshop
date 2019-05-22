<?php

namespace WTG\Providers;

use Illuminate\Support\Facades\Gate;
use WTG\Models\Customer;
use WTG\Models\Registration;
use WTG\Services\AuthService;
use WTG\Policies\SubAccountPolicy;
use WTG\Services\RegistrationService;
use WTG\Contracts\Models\RegistrationContract;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Contracts\Services\RegistrationServiceContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Auth service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Customer::class => SubAccountPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('subaccounts-view', 'WTG\Policies\SubAccountPolicy@view');
        Gate::define('subaccounts-assign-admin', 'WTG\Policies\SubAccountPolicy@assignAdminRole');
        Gate::define('subaccounts-assign-manager', 'WTG\Policies\SubAccountPolicy@assignManagerRole');

        $this->app->bind(AuthServiceContract::class, AuthService::class);
        $this->app->bind(RegistrationContract::class, Registration::class);
        $this->app->bind(RegistrationServiceContract::class, RegistrationService::class);
        $this->app->bind(\WTG\Contracts\Services\Admin\AuthServiceContract::class, \WTG\Services\Admin\AuthService::class);
    }
}

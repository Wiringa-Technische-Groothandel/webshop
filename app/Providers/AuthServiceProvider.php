<?php

declare(strict_types=1);

namespace WTG\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use WTG\Contracts\Models\RegistrationContract;
use WTG\Contracts\Services\AuthManagerContract;
use WTG\Contracts\Services\RegistrationServiceContract;
use WTG\Managers\AuthManager;
use WTG\Models\Customer;
use WTG\Models\Registration;
use WTG\Policies\SubAccountPolicy;
use WTG\Services\RegistrationService;

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

        $this->app->bind(AuthManagerContract::class, AuthManager::class);
        $this->app->bind(RegistrationContract::class, Registration::class);
        $this->app->bind(RegistrationServiceContract::class, RegistrationService::class);
    }
}

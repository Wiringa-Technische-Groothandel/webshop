<?php

declare(strict_types=1);

namespace WTG\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use WTG\Contracts\Models\RegistrationContract;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Contracts\Services\RegistrationServiceContract;
use WTG\Foundation\Auth\Guards\AdminGuard;
use WTG\Foundation\Auth\Guards\WebGuard;
use WTG\Models\Customer;
use WTG\Models\Registration;
use WTG\Policies\SubAccountPolicy;
use WTG\Services\AuthService;
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

        $this->app->bind(AuthServiceContract::class, AuthService::class);
        $this->app->bind(RegistrationContract::class, Registration::class);
        $this->app->bind(RegistrationServiceContract::class, RegistrationService::class);

        Auth::resolved(
            function ($auth) {
                $auth->viaRequest('airlock-admin', new AdminGuard($auth, config('airlock.expiration')));
            }
        );

        Auth::resolved(
            function ($auth) {
                $auth->viaRequest('airlock-web', new WebGuard($auth, config('airlock.expiration')));
            }
        );
    }

    /**
     * Register the provider.
     *
     * @return void
     */
    public function register()
    {
        config(
            [
                'auth.guards.airlock-admin' => array_merge(
                    [
                        'driver'   => 'airlock-admin',
                        'provider' => 'admins',
                    ],
                    config('auth.guards.airlock-admin', [])
                ),
            ]
        );

        config(
            [
                'auth.guards.airlock-web' => array_merge(
                    [
                        'driver'   => 'airlock-web',
                        'provider' => 'admins',
                    ],
                    config('auth.guards.airlock-web', [])
                ),
            ]
        );
    }
}

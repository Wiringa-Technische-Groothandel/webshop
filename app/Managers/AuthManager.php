<?php

declare(strict_types=1);

namespace WTG\Managers;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use WTG\Contracts\Services\AuthManagerContract;
use WTG\Models\Company;
use WTG\Models\Customer;

/**
 * Authentication manager.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AuthManager implements AuthManagerContract
{
    private AuthFactory $auth;
    private string $guard;

    /**
     * AuthService constructor.
     *
     * @param AuthFactory $auth
     */
    public function __construct(AuthFactory $auth)
    {
        $this->lighthouseGuard = config('lighthouse.guard', 'sanctum');
        $this->sanctumGuard = config('sanctum.guard', 'web');
        $this->auth = $auth;
    }

    /**
     * Authenticate a user by request.
     *
     * @param Request $request
     * @return null|Customer
     */
    public function authenticateByRequest(Request $request): ?Customer
    {
        return $this->authenticate(
            (string) $request->input('company'),
            (string) $request->input('username'),
            (string) $request->input('password'),
            (bool) $request->input('remember', false)
        );
    }

    public function authenticate(
        string $customerNumber,
        string $username,
        string $password,
        bool $remember = false
    ): ?Customer {
        /** @var Company $company */
        $company = Company::query()
            ->where('customer_number', $customerNumber)
            ->where('active', true)
            ->first();

        if ($company === null) {
            return null;
        }

        $loginData = [
            'company_id' => $company->getAttribute('id'),
            'username'   => $username,
            'password'   => $password,
            'active'     => true,
        ];

        $this->auth->guard($this->sanctumGuard)->attempt($loginData, $remember);

        return $this->getCurrentCustomer();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return null|Customer
     */
    public function getCurrentCustomer(): ?Customer
    {
        return $this->auth->guard($this->lighthouseGuard)->user();
    }

    /**
     * Logout the current user.
     *
     * @return void
     */
    public function logout(): void
    {
        $customer = $this->getCurrentCustomer();

        if (! $customer) {
            return;
        }

        $customer->currentAccessToken()->delete();
    }
}

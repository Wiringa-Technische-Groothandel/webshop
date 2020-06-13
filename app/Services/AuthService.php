<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Models\Company;
use WTG\Models\Customer;

/**
 * Authentication service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AuthService implements AuthServiceContract
{
    /**
     * @var AuthFactory
     */
    protected AuthFactory $auth;

    /**
     * AuthService constructor.
     *
     * @param AuthFactory $auth
     */
    public function __construct(AuthFactory $auth)
    {
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
            $request->input('company'),
            $request->input('username'),
            $request->input('password'),
            $request->input('remember', false),
        );
    }

    /**
     * Authenticate a user.
     *
     * @param string $customerNumber
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @return Customer|null
     */
    public function authenticate(
        string $customerNumber,
        string $username,
        string $password,
        bool $remember = false
    ): ?Customer {
        /** @var Company $company */
        $company = Company::query()
            ->where('customer_number', $customerNumber)
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

        $this->auth->guard()->attempt($loginData, $remember);

        return $this->getCurrentCustomer();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return null|Customer
     */
    public function getCurrentCustomer(): ?Customer
    {
        return $this->auth->guard()->user();
    }
}

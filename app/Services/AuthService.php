<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\AuthServiceInterface;
use WTG\Models\Company;

/**
 * Authentication service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AuthService implements AuthServiceInterface
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
     * @return null|CustomerContract
     * @throws BindingResolutionException
     */
    public function authenticateByRequest(Request $request): ?CustomerContract
    {
        return $this->authenticate(
            $request->input('company', ''),
            $request->input('username', ''),
            $request->input('password', ''),
            $request->input('remember', false)
        );
    }

    /**
     * Authenticate a user.
     *
     * @param string $customerNumber
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @return CustomerContract|null
     * @throws BindingResolutionException
     */
    public function authenticate(
        string $customerNumber,
        string $username,
        string $password,
        bool $remember = false
    ): ?CustomerContract {
        /** @var Company $company */
        $company = app()->make(CompanyContract::class)
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
     * Logout a user.
     *
     * @return bool
     */
    public function logout(): bool
    {
        $guard = $this->auth->guard();

        if ($guard instanceof StatefulGuard) {
            $guard->logout();

            return true;
        }

        return false;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return null|CustomerContract
     */
    public function getCurrentCustomer(): ?CustomerContract
    {
        return $this->auth->guard()->user();
    }
}

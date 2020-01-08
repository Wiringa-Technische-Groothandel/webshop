<?php

declare(strict_types=1);

namespace WTG\Services;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\AuthServiceContract;
use WTG\Models\Company;

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
     * @return null|CustomerContract
     * @throws BindingResolutionException
     */
    public function authenticateByRequest(Request $request): ?CustomerContract
    {
        /** @var Company $company */
        $company = app()->make(CompanyContract::class)
            ->where('customer_number', $request->input('company'))
            ->first();

        if ($company === null) {
            return null;
        }

        $loginData = [
            'company_id' => $company->getAttribute('id'),
            'username'   => $request->input('username'),
            'password'   => $request->input('password'),
            'active'     => true,
        ];

        $this->auth->guard()->attempt($loginData, $request->input('remember', false));

        return $this->getCurrentCustomer();
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

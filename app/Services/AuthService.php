<?php

namespace WTG\Services;

use WTG\Models\Company;
use Illuminate\Http\Request;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Services\AuthServiceContract;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

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
    protected $auth;

    /**
     * AuthService constructor.
     *
     * @param  AuthFactory  $auth
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Authenticate a user by request.
     *
     * @param  Request  $request
     * @return null|CustomerContract
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

        $login_data = [
            'company_id' => $company->getAttribute('id'),
            'username'   => $request->input('username'),
            'password'   => $request->input('password'),
            'active'     => true
        ];

        $this->auth->guard()->attempt($login_data, $request->input('remember', false));

        return $request->user();
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
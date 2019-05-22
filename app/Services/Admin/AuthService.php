<?php

namespace WTG\Services\Admin;

use WTG\Contracts\Models\AdminContract;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use WTG\Contracts\Services\Admin\AuthServiceContract;

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
     * @param  string  $username
     * @param  string  $password
     * @return null|AdminContract
     * @throws AuthenticationException
     */
    public function authenticate(string $username, string $password): ?AdminContract
    {
        $credentials = [
            'username'   => $username,
            'password'   => $password
        ];

        if (! $this->auth->guard('admin')->attempt($credentials)) {
            throw new AuthenticationException(__('Gebruikersnaam en/of wachtwoord onjuist.'), ['admin']);
        }

        return $this->getCurrentCustomer();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return null|AdminContract
     */
    public function getCurrentCustomer(): ?AdminContract
    {
        return $this->auth->guard('admin')->user();
    }

    /**
     * Logout the current user.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->auth->guard('admin')->logout();
    }
}
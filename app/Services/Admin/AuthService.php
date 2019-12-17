<?php

declare(strict_types=1);

namespace WTG\Services\Admin;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use WTG\Models\Admin;

/**
 * Authentication service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AuthService
{
    private const GUARD_NAME = 'admin';

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
     * @param string|null $username
     * @param string|null $password
     *
     * @return string
     * @throws AuthenticationException
     */
    public function authenticate(?string $username, ?string $password): string
    {
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];

        $token = $this->auth->guard(self::GUARD_NAME)->attempt($credentials);

        if (! $token) {
            throw new AuthenticationException(__('Gebruikersnaam en/of wachtwoord onjuist.'), [self::GUARD_NAME]);
        }

        return $token;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return null|Admin
     */
    public function getUser(): ?Admin
    {
        return $this->auth->guard(self::GUARD_NAME)->user();
    }

    /**
     * Logout the current user.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->auth->guard(self::GUARD_NAME)->logout();
    }

    /**
     * @return mixed|void
     */
    public function getPayload()
    {
        return $this->auth->guard(self::GUARD_NAME)->payload();
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->auth->guard(self::GUARD_NAME)->check();
    }

    /**
     * @return string
     */
    public function refresh(): string
    {
        return $this->auth->guard(self::GUARD_NAME)->refresh();
    }

    /**
     * @return int
     */
    public function getExpiryTime(): int
    {
        return time() + ($this->auth->guard(self::GUARD_NAME)->factory()->getTTL() * 60);
    }
}

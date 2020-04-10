<?php

declare(strict_types=1);

namespace WTG\Contracts\Services;

use Illuminate\Http\Request;
use WTG\Contracts\Models\CustomerContract;

/**
 * Auth service interface.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AuthServiceInterface
{
    /**
     * Authenticate a user by request.
     *
     * @param Request $request
     * @return null|CustomerContract
     */
    public function authenticateByRequest(Request $request): ?CustomerContract;

    /**
     * Authenticate a user.
     *
     * @param string $customerNumber
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @return CustomerContract|null
     */
    public function authenticate(
        string $customerNumber,
        string $username,
        string $password,
        bool $remember = false
    ): ?CustomerContract;

    /**
     * Logout a user.
     *
     * @return bool
     */
    public function logout(): bool;

    /**
     * Get the currently authenticated user.
     *
     * @return null|CustomerContract
     */
    public function getCurrentCustomer(): ?CustomerContract;
}

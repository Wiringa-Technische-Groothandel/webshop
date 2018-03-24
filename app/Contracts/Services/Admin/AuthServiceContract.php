<?php

namespace WTG\Contracts\Services\Admin;

use WTG\Contracts\Models\AdminContract;

/**
 * Admin auth service contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Services\Admin
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AuthServiceContract
{
    /**
     * Authenticate a user by request.
     *
     * @param  string  $username
     * @param  string  $password
     * @return null|AdminContract
     */
    public function authenticate(string $username, string $password): ?AdminContract;

    /**
     * Get the currently authenticated user.
     *
     * @return null|AdminContract
     */
    public function getCurrentCustomer(): ?AdminContract;

    /**
     * Logout the current user.
     *
     * @return void
     */
    public function logout(): void;
}
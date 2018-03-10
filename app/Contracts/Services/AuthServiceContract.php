<?php

namespace WTG\Contracts\Services;

use Illuminate\Http\Request;
use WTG\Contracts\Models\CustomerContract;

/**
 * Interface AuthServiceContract
 *
 * @package     WTG\Contracts
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AuthServiceContract
{
    /**
     * Authenticate a user by request.
     *
     * @param  Request  $request
     * @return null|CustomerContract
     */
    public function authenticateByRequest(Request $request): ?CustomerContract;

    /**
     * Get the currently authenticated user.
     *
     * @return null|CustomerContract
     */
    public function getCurrentCustomer(): ?CustomerContract;
}
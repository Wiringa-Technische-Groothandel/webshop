<?php

declare(strict_types=1);

namespace WTG\Contracts\Services;

use WTG\Contracts\Models\RegistrationContract;

/**
 * Registration service contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface RegistrationServiceContract
{
    /**
     * Create a registration from request.
     *
     * @param  array  $data
     * @return RegistrationContract
     */
    public function create(array $data): RegistrationContract;
}
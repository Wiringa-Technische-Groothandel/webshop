<?php

namespace WTG\Services;

use WTG\Models\Registration;
use WTG\Contracts\Models\RegistrationContract;
use WTG\Contracts\Services\RegistrationServiceContract;

/**
 * Registration service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RegistrationService implements RegistrationServiceContract
{
    /**
     * Create a registration from request.
     *
     * @param  array  $data
     * @return RegistrationContract
     */
    public function create(array $data): RegistrationContract
    {
        /** @var Registration $registration */
        $registration = app()->make(RegistrationContract::class);
        $registration->fill($data);
        $registration->save();

        return $registration;
    }
}
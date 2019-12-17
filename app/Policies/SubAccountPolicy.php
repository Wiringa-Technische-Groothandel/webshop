<?php

declare(strict_types=1);

namespace WTG\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use WTG\Models\Customer;
use WTG\Models\Role;

class SubAccountPolicy
{
    use HandlesAuthorization;

    /**
     * View the list of sub-accounts.
     *
     * @param Customer $user
     * @return mixed
     */
    public function view(Customer $user)
    {
        return $user->getRole()->getLevel() >= Role::ROLE_MANAGER;
    }

    /**
     * Determine whether the user can assign the manager role.
     *
     * @param Customer $user
     * @return mixed
     */
    public function assignManagerRole(Customer $user)
    {
        return $user->getRole()->getLevel() >= Role::ROLE_MANAGER;
    }
}

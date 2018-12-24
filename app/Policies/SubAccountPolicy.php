<?php

namespace WTG\Policies;

use WTG\Models\Role;
use WTG\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubAccountPolicy
{
    use HandlesAuthorization;

    /**
     * View the list of sub-accounts.
     *
     * @param  \WTG\Models\Customer  $user
     * @return mixed
     */
    public function view(Customer $user)
    {
        return $user->getRole()->getLevel() >= Role::ROLE_MANAGER;
    }

    /**
     * Determine whether the user can assign the admin role.
     *
     * @param  \WTG\Models\Customer  $user
     * @return mixed
     */
    public function assignAdminRole(Customer $user)
    {
        return $user->getRole()->getLevel() >= Role::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can assign the manager role.
     *
     * @param  \WTG\Models\Customer  $user
     * @return mixed
     */
    public function assignManagerRole(Customer $user)
    {
        return $user->getRole()->getLevel() >= Role::ROLE_MANAGER;
    }
}

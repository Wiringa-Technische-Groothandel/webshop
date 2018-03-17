<?php

use WTG\Models\Customer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class CreateRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $visitAdminPanelPermission = Permission::create(['name' => 'visit admin panel']);
//        $assignAdminRolePermission = Permission::create(['name' => 'assign admin role']);
        $assignManagerRolePermission = Permission::create(['name' => 'assign manager role']);
        $viewAccountsPermission = Permission::create(['name' => 'view accounts']);

        $superAdminRole = Role::create(['name' => Customer::CUSTOMER_ROLE_SUPER_ADMIN]);
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole = Role::create(['name' => Customer::CUSTOMER_ROLE_ADMIN]);
        // Disabled for now, re-enable when permission stuff is worked out.
//        $adminRole->givePermissionTo($assignAdminRolePermission);
        $adminRole->givePermissionTo($assignManagerRolePermission);
        $adminRole->givePermissionTo($viewAccountsPermission);

        $managerRole = Role::create(['name' => Customer::CUSTOMER_ROLE_MANAGER]);
        $managerRole->givePermissionTo($assignManagerRolePermission);
        $managerRole->givePermissionTo($viewAccountsPermission);

        $userRole = Role::create(['name' => Customer::CUSTOMER_ROLE_USER]);
        $userRole->givePermissionTo($viewAccountsPermission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::getQuery()->delete();
        Permission::getQuery()->delete();
    }
}

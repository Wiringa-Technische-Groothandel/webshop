<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level', false, true);
            $table->string('name');
            $table->timestamps();
        });

        $roles = [
            ['level' => \WTG\Models\Role::ROLE_USER, 'name' => 'user'],
            ['level' => \WTG\Models\Role::ROLE_MANAGER, 'name' => 'manager'],
        ];

        foreach ($roles as $role) {
            \WTG\Models\Role::create($role);
        }

        $userRoleId = \WTG\Models\Role::level(\WTG\Models\Role::ROLE_USER)->first()->getId();

        Schema::table('customers', function (Blueprint $table) use ($userRoleId) {
            $table->integer('role_id', false, true)->default($userRoleId);
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('roles');
    }
}

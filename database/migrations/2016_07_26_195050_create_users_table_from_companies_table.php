<?php

use App\User;
use App\Company;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTableFromCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20)->unique();
            $table->string('company_id');
            $table->string('email', 50);
            $table->boolean('active');
            $table->boolean('isAdmin')->default('0');
            $table->boolean('manager')->default('0');
            $table->string('password');
            $table->string('favorites', 8000)->default('a:0:{}');
            $table->mediumText('cart')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $companies = Company::all();

        foreach ($companies as $company) {
            $user = new User();

            $user->username = $company->login;
            $user->password = $company->password;
            $user->company_id = $company->login;
            $user->email = $company->email;
            $user->active = true;
            $user->isAdmin = $company->isAdmin;
            $user->favorites = $company->favorites;
            $user->cart = $company->cart;
            $user->created_at = $company->created_at;
            $user->updated_at = $company->updated_at;
            $user->manager = true;

            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

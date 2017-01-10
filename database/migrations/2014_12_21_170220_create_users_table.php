<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');                // Autoincrement : ID
            $table->string('login', 10)->unique();            // VARCHAR(10)	 : Username
            $table->string('company', 255);                // VARCHAR(255)	 : Company Name
            $table->string('street', 100);                // VARCHAR(100)	 : Street + Nr
            $table->string('postcode', 7);                // VARCHAR(7)	 : Postcode: XXXX YY
            $table->string('city', 50);                // VARCHAR(50)	 : City
            $table->string('email', 50);                // VARCHAR(50)	 : Email address
            $table->boolean('active');                // BOOLEAN	 : Active (if this is true, the user is able to login)
            $table->boolean('isAdmin')->default('0');        // BOOLEAN	 : Is the user an admin or not? (Default = false)
            $table->string('password');                // VARCHAR(255)	 : Password
            $table->string('favorites', 8000)->default('a:0:{}');    // VARCHAR(8000) : A serialized array containing the favorite products from the user
            $table->mediumText('cart')->nullable();            // MEDIUMTEXT	 : The cart is saved here between sessions
            $table->rememberToken();
            $table->timestamps();
        });
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

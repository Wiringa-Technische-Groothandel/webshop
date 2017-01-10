<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');            // Autoincrement : ID
            $table->string('name', 255);            // VARCHAR(255)	 : Name of the person or company located at the address
            $table->string('street', 100);            // VARCHAR(100)	 : Street + Nr
            $table->string('city', 50);            // VARCHAR(50)	 : City
            $table->string('postcode', 7);            // VARCHAR(7)	 : Postcode as -> XXXX YY
            $table->string('telephone', 15)->nullable();    // VARCHAR(15)	 : Telephone number (optional)
            $table->string('mobile', 15)->nullable();    // VARCHAR(15)	 : Mobile number (optional)
            $table->integer('User_id');            // INTEGER       : The user that this address is linked to
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
        Schema::drop('addresses');
    }
}

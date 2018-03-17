<?php

use Illuminate\Support\Facades\Schema;
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
            $table->increments('id');
            $table->integer('company_id', false, true);
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('name', 150);
            $table->string('street', 50);
            $table->string('postcode', 10);
            $table->string('city', 75);
            $table->string('phone', 15)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->boolean('is_default')->default(false);
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
        Schema::dropIfExists('addresses');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     * This migration is optional for most users as it's specifically designed for wiringa.nl.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');               // Autoincrement : ID
                        $table->string('table', 7);             // VARCHAR(7)    : This will specify the type of discount (Required in my situation)
                        $table->integer('User_id');             // INTEGER       : The user id that the discount is linked to
                        $table->integer('product');             // INTEGER       : Product group/number
                        $table->string('start_date', 25);       // VARCHAR(25)   : Start date of the discount
                        $table->string('end_date', 25);         // VARCHAR(25)   : End date of the discount
                        $table->string('discount');             // VARCHAR(5)    : The actual discount as percentage
                        $table->string('group_desc', 30);       // VARCHAR(30)   : Short product group description
                        $table->string('product_desc', 35);     // VARCHAR(35)   : Short product description
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
        Schema::drop('discounts');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarouselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carousel', function (Blueprint $table) {
            $table->increments('id');                // Autoincrement : ID
            $table->string('Image', 50);            // VARCHAR(50)   : Image name
            $table->string('Title', 100);            // VARCHAR(100)  : Title
            $table->string('Caption', 200);            // VARCHAR(200)  : Subtitle
            $table->integer('Order');                // Integer	     : Order identifier
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
        Schema::drop('carousel');
    }
}

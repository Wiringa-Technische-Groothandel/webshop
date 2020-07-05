<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProductPriceFactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('product_price_factors');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('product_price_factors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('erp_id', 50)->index();
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->decimal('price_per');
            $table->decimal('price_factor');
            $table->string('scale_unit', 10);
            $table->string('price_unit', 10);
            $table->timestamps();
            $table->timestamp('synchronized_at')->nullable(true);
        });
    }
}

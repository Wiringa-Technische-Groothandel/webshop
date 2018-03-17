<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sku')->unique();
            $table->integer('group');
            $table->string('name', 75);
            $table->string('ean', 16);
            $table->string('sales_unit', 5);
            $table->string('packing_unit', 5);
            $table->unsignedDecimal('length', 10, 2);
            $table->unsignedDecimal('height', 10, 2);
            $table->unsignedDecimal('width', 10, 2);
            $table->unsignedDecimal('weight', 10, 2);
            $table->unsignedDecimal('vat', 10, 2);
            $table->boolean('discontinued')->default(false);
            $table->boolean('blocked')->default(false);
            $table->boolean('inactive')->default(false);
            $table->string('brand', 50);
            $table->string('series', 50);
            $table->string('type', 50);
            $table->string('keywords', 100)->nullable();
            $table->string('related', 85)->nullable();
            $table->string('catalog_group', 50);
            $table->string('catalog_index', 100);
            $table->timestamps();
            $table->timestamp('synchronized_at')->nullable();

            $table->index([
                'name',
                'sku',
                'group',
                'brand',
                'series',
                'type'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
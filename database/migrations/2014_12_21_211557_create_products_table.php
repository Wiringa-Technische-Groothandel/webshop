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
            $table->increments('id');            // Autoincrement : ID
            $table->string('name', 75);            // VARCHAR(75)	 : Product name
            $table->integer('number')->unique();        // INTEGER 	 : Product number -> must be unique
            $table->integer('group');            // INTEGER	 : Product group
            $table->string('altNumber', 16);        // VARCHAR(16)	 : Alternative product number
            $table->string('stockCode', 3);            // VARCHAR(3)	 : Stock code for the product
            $table->string('registered_per', 5);        // VARCHAR(5)	 : Product registered per. Example: piece, meter
            $table->string('packed_per', 5);        // VARCHAR(5)	 : Product sold per. Example: box, spool
            $table->string('price_per', 5);            // VARCHAR(5)	 : Price per piece/box/bag
            $table->string('refactor', 6);            // VARCHAR(6)	 : Price refactoring (example: product price is per meter but it's sold per piece)
            $table->string('supplier', 13)->nullable();    // VARCHAR(13)	 : Supplier of the product -> optional
            $table->string('ean', 16);            // VARCHAR(16)	 : EAN Code
            $table->string('image', 34);            // VARCHAR(34)	 : Name for the image file
            $table->string('length', 9)->nullable();    // VARCHAR(9)	 : Length of the product -> optional
            $table->string('price', 10);            // VARCHAR(10) 	 : Price
            $table->integer('vat');                    // INTEGER       : VAT
            $table->string('brand', 50);            // VARCHAR(50)	 : Brand
            $table->string('series', 50);            // VARCHAR(50)	 : Series
            $table->string('type', 50);            // VARCHAR(50)	 : Type
            $table->decimal('special_price', 10, 2);    // DECIMAL(10,2) : Static price, ignores discounts
            $table->string('action_type', 10);        // VARCHAR(10)	 : Type of action. Example: Temporary discount or clearing
            $table->string('keywords', 40);            // VARCHAR(40)	 : Extra search keywords
            $table->string('related_products', 85);         // VARCHAR(50)   : Comma separated list of related product numbers
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
        Schema::drop('products');
    }
}

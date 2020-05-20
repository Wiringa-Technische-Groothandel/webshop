<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOndeleteToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')->on('companies')->references('id')->onDelete('cascade');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')->on('companies')->references('id')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('company_id', false, true)->nullable(true)->change();

            $table->dropForeign(['company_id']);
            $table->foreign('company_id')->on('companies')->references('id')->onDelete('set null');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->foreign('customer_id')->on('customers')->references('id')->onDelete('cascade');
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropForeign(['quote_id']);
            $table->dropForeign(['product_id']);
            $table->foreign('quote_id')->on('quotes')->references('id')->onDelete('cascade');
            $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropForeign(['quote_id']);
            $table->dropForeign(['product_id']);
            $table->foreign('quote_id')->on('quotes')->references('id');
            $table->foreign('product_id')->on('products')->references('id');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->foreign('customer_id')->on('customers')->references('id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')->on('companies')->references('id');

            $table->integer('company_id', false, true)->nullable(false)->change();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')->on('companies')->references('id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->foreign('company_id')->on('companies')->references('id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditProductsTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_sku_unique');
            $table->dropIndex('products_name_sku_group_brand_series_type_index');

            $table->unique([
                'sku',
                'sales_unit'
            ]);

            $table->index('sku');
            $table->index('group');
            $table->index('name');
            $table->index([
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_brand_series_type_index');
            $table->dropIndex('products_name_index');
            $table->dropIndex('products_group_index');
            $table->dropIndex('products_sku_index');

            $table->dropUnique([
                'sku',
                'sales_unit'
            ]);

            $table->index([
                'name',
                'sku',
                'group',
                'brand',
                'series',
                'type'
            ]);
            $table->unique('sku');
        });
    }
}

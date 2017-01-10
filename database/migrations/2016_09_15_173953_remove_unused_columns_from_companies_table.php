<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedColumnsFromCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('password');
            $table->dropColumn('favorites');
            $table->dropColumn('cart');
            $table->dropColumn('remember_token');
            $table->dropColumn('isAdmin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('password');
            $table->string('favorites', 8000)->default('a:0:{}');
            $table->mediumText('cart')->nullable();
            $table->boolean('isAdmin')->default(false);
            $table->rememberToken();
        });
    }
}

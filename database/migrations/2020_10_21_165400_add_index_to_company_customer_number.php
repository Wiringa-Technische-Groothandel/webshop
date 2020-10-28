<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToCompanyCustomerNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'companies',
            function (Blueprint $table) {
                $table->index('customer_number');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'companies',
            function (Blueprint $table) {
                $table->dropIndex(
                    [
                        'customer_number'
                    ]
                );
            }
        );
    }
}

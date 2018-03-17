<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressIdToContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->integer('address_id', false, true)->nullable()->after('customer_id');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign([
                'address_id'
            ]);
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'address_id'
            ]);
        });
    }
}

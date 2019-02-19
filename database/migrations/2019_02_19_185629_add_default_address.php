<?php

use WTG\Models\Address;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->integer('company_id', false, true)->nullable(true)->change();
        });

        Address::unguard();
        /** @var Address $address */
        $address = Address::create([
            'id' => Address::DEFAULT_ID,
            'name' => 'Afhalen op locatie',
            'street' => 'Bovenstreek 1',
            'postcode' => '9731 DH',
            'city' => 'Groningen',
        ]);
        Address::reguard();

        \DB::statement('UPDATE addresses SET id = ? WHERE id = ?', [
            Address::DEFAULT_ID, $address->getId()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Address::destroy(Address::DEFAULT_ID);

        Schema::table('addresses', function (Blueprint $table) {
            $table->integer('company_id', false, true)->nullable(false)->change();
        });
    }
}

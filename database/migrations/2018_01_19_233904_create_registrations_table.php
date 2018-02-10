<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('contact-company');
            $table->string('contact-name');
            $table->string('contact-address');
            $table->string('contact-city');
            $table->string('contact-postcode');
            $table->string('contact-phone-company');
            $table->string('contact-phone');
            $table->string('contact-email');
            $table->string('contact-website')->nullable();

            $table->boolean('copy-contact')->default(false);
            $table->string('business-address');
            $table->string('business-city');
            $table->string('business-postcode');
            $table->string('business-phone');

            $table->string('payment-iban');
            $table->string('payment-kvk');
            $table->string('payment-vat');

            $table->string('other-alt-email')->nullable();
            $table->boolean('other-order-confirmation');
            $table->boolean('other-mail-productfile');

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
        Schema::dropIfExists('registrations');
    }
}

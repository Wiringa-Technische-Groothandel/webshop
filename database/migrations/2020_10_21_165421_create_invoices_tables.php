<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->nullOnDelete();
            $table->string('customer_number');
            $table->string('erp_id')->index();
            $table->string('invoice_number');
            $table->date('invoice_date')->index();
            $table->date('due_date')->nullable();
            $table->string('description')->nullable();
            $table->decimal('subtotal');
            $table->decimal('vat');
            $table->decimal('total');
            $table->string('vat_code');
            $table->string('status_code');
            $table->string('status_description');
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
        Schema::dropIfExists('invoices');
    }
}

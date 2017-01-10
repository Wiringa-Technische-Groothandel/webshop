<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHiddenFieldToTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('text', function (Blueprint $table) {
            $table->boolean('hidden')->default(false);
        });

        DB::table('text')->insert([
            ['name' => 'admin.product_import', 'title' => 'Product import', 'page' => 'N/A', 'content' => 'Nog geen import uitgevoerd', 'hidden' => true, 'created_at' => Carbon::now('Europe/Amsterdam')],
            ['name' => 'admin.discount_import', 'title' => 'Korting import', 'page' => 'N/A', 'content' => 'Nog geen import uitgevoerd', 'hidden' => true, 'created_at' => Carbon::now('Europe/Amsterdam')],
            ['name' => 'catalog.footer', 'title' => 'Catalogus footer', 'page' => 'N/A', 'content' => 'iets', 'hidden' => true, 'created_at' => Carbon::now('Europe/Amsterdam')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('text', function (Blueprint $table) {
            $table->dropColumn('hidden');
        });
    }
}

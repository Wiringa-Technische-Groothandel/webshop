<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkAndLinkTypeColumnsToCarouselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carousel', function (Blueprint $table) {
            $table->string('link')->nullable();
            $table->string('link_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carousel', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'link',
                    'link_type'
                ]
            );
        });
    }
}

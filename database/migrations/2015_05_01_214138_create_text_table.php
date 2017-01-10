<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');                         // VARCHAR(255) : The system name
                        $table->string('title');                        // VARCHAR(255) : The field name
                        $table->string('page');                         // VARCHAR(255) : The page that the content belongs to
                        $table->binary('content');                      // BLOB         : This will contain the text for the pages (default: empty)
            $table->timestamps();
        });

        DB::table('text')->insert([
                        ['name' => 'downloads.flyers', 'title' => 'Flyers', 'page' => 'Downloads', 'content' => ''],
                        ['name' => 'downloads.catalogus', 'title' => 'Catalogus', 'page' => 'Downloads', 'content' => ''],
                        ['name' => 'downloads.artikel', 'title' => 'Artikel bestanden', 'page' => 'Downloads', 'content' => ''],
                        ['name' => 'home.news', 'title' => 'Nieuws', 'page' => 'Home', 'content' => ''],
                ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('text');
    }
}

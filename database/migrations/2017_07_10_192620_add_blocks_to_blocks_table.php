<?php

use WTG\Models\Block;
use Illuminate\Database\Migrations\Migration;

class AddBlocksToBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createBlock('news', 'Nieuws', '<h3>Dit is het nieuws blok</h3>');
        $this->createBlock('about', 'Over ons', '<h3>Dit is het over ons blok</h3>');
        $this->createBlock('downloads.catalog', 'Catalogus', '<h3>Dit is het catalogus blok</h3>');
        $this->createBlock('downloads.flyers', 'Flyers', '<h3>Dit is het flyers blok</h3>');
        $this->createBlock('downloads.products', 'Artikelbestanden', '<h3>Dit is het artikelbestanden blok</h3>');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    /**
     * Create a new block.
     *
     * @param  string  $name
     * @param  string  $title
     * @param  string  $content
     * @return Block
     */
    private function createBlock($name, $title, $content): Block
    {
        $block = new Block;

        $block->setAttribute('name', $name);
        $block->setAttribute('title', $title);
        $block->setAttribute('content', $content);

        $block->save();

        return $block;
    }
}

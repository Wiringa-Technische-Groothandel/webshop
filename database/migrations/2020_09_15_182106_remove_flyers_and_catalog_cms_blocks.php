<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use WTG\Models\Block;

class RemoveFlyersAndCatalogCmsBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('blocks')->whereIn(
            'name',
            ['downloads.flyers', 'downloads.catalog']
        )->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->createBlock('downloads.catalog', 'Catalogus', '<h3>Dit is het catalogus blok</h3>');
        $this->createBlock('downloads.flyers', 'Flyers', '<h3>Dit is het flyers blok</h3>');
    }

    /**
     * Create a new block.
     *
     * @param string $name
     * @param string $title
     * @param string $content
     * @return Block
     */
    private function createBlock(string $name, string $title, string $content): Block
    {
        $block = new Block();

        $block->setAttribute('name', $name);
        $block->setAttribute('title', $title);
        $block->setAttribute('content', $content);

        $block->save();

        return $block;
    }
}

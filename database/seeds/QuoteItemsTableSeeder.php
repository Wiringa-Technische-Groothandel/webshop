<?php

use Illuminate\Database\Seeder;
use WTG\Models\QuoteItem;

/**
 * Quote items table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class QuoteItemsTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        QuoteItem::create([
            'id' => 1,
            'product_id' => 1,
            'quote_id' => 1,
            'qty' => 2,
            'price' => 12,
            'subtotal' => 24
        ]);
    }
}

<?php

use WTG\Models\Quote;

/**
 * Quotes table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class QuotesTableSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        Quote::create([
            'customer_id' => 1,
        ]);
    }
}
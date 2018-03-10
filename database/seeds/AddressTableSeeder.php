<?php

use WTG\Models\Address;

/**
 * Address table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressTableSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
            'company_id' => 1,
            'name'       => 'Breezehome',
            'street'     => 'Foobar 1',
            'postcode'   => '1234 AB',
            'city'       => 'Whiterun',
            'phone'      => '0987654321',
            'mobile'     => '0123456789'
        ]);
    }
}
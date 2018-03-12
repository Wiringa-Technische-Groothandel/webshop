<?php

use WTG\Models\Company;

/**
 * Companies table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CompaniesTableSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'id' => 1,
            'customer_number'   => '12345',
            'name'              => 'Test company 1',
            'street'            => 'Street 1',
            'postcode'          => '1234 XX',
            'city'              => 'Somewhere',
            'active'            => true
        ]);
    }
}
<?php

use WTG\Models\Customer;

/**
 * Customers table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CustomersTableSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'id' => 1,
            'company_id' => 1,
            'username'   => 'foobar',
            'password'   => bcrypt('test'),
            'active'     => true
        ]);
    }
}
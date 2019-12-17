<?php

use Illuminate\Database\Seeder;
use WTG\Models\Contact;
use WTG\Models\Customer;

/**
 * Customers table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CustomersTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $customer = Customer::create([
            'id' => 1,
            'company_id' => 1,
            'username'   => 'foobar',
            'password'   => bcrypt('test'),
            'active'     => true
        ]);

        Contact::create(
            [
                'id' => 1,
                'customer_id' => $customer->getId(),
                'contact_email' => 'contact@email.com',
                'order_email' => 'order@email.com'
            ]
        );
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use WTG\Models\Company;

/**
 * Companies table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CompaniesTableSeeder extends Seeder
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

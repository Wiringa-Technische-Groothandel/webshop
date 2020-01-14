<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Database seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompaniesTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(AddressTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(QuotesTableSeeder::class);
        $this->call(QuoteItemsTableSeeder::class);
        $this->call(RegistrationsTableSeeder::class);
    }
}

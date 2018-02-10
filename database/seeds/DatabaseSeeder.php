<?php

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
         $this->call(RegistrationsTableSeeder::class);
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use WTG\Models\Product;

/**
 * Products table seeder.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        Product::withoutSyncingToSearch(function () {
            Product::create(
                [
                    'id'            => 1,
                    'erp_id'        => '1234,STK',
                    'sku'           => '1234',
                    'group'         => '4321',
                    'name'          => 'Test product 1',
                    'supplier_code' => '1234',
                    'ean'           => '12341234',
                    'sales_unit'    => 'STK',
                    'packing_unit'  => 'NVP',
                    'length'        => 0,
                    'height'        => 0,
                    'width'         => 0,
                    'weight'        => 0,
                    'vat'           => 21,
                    'discontinued'  => false,
                    'blocked'       => false,
                    'inactive'      => false,
                    'brand'         => 'Foo',
                    'series'        => 'Bar',
                    'type'          => 'Baz',
                    'keywords'      => 'foo bar baz',
                ]
            );
        });
    }
}

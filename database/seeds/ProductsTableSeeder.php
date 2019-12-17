<?php

use Illuminate\Database\Seeder;
use WTG\Catalog\Model\PriceFactor;
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
            $product = Product::create(
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

            PriceFactor::create(
                [
                    'id'           => 1,
                    'erp_id'       => '1234,STK,FOO',
                    'product_id'   => $product->getId(),
                    'price_per'    => 1.0,
                    'price_factor' => 2.0,
                    'scale_unit'   => 'DS',
                    'price_unit'   => 'STK',
                ]
            );
        });
    }
}

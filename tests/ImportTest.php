<?php

use App\Product;
use App\Discount;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImportTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test importing without a discount csv.
     *
     * @return void
     */
    public function testWithoutDiscountsFile()
    {
        $exitCode = Artisan::call('import:discounts');

        $this->assertEquals(1, $exitCode);
    }

    /**
     * Test importing without a product csv.
     *
     * @return void
     */
    public function testWithoutProductsFile()
    {
        $exitCode = Artisan::call('import:products');

        $this->assertEquals(1, $exitCode);
    }

    /**
     * Test importing with all fields in products.
     *
     * @return void
     */
    public function testValidProductsFile()
    {
        $contents = 'Someproduct;NE;BDY;1000030;10900100;20020658;1;A;Stk;Stk;1;HG;1;Dyka;8716936000541;zw0042005.jpg;1;;0,02;21;8716936000008;Dyka;Dyka krimpmoffen;O ringen voor krimpmoffen;;;Dijka;;A. Vuil en hemelwater leidingsystemen;O ringen voor krimpmoffen';

        File::put(storage_path('import/products.csv'), $contents);

        $exitCode = Artisan::call('import:products');

        $this->assertEquals(0, $exitCode);

        $product = Product::first();

        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test importing with all fields in discounts.
     *
     * @return void
     */
    public function testValidDiscountsFile()
    {
        $contents = 'VA-220;69696;20125125;18-2-2008 0:00:00;31-12-9999 0:00:00;33;Grohe onderdelen;';

        File::put(storage_path('import/discounts.csv'), $contents);

        $exitCode = Artisan::call('import:discounts');

        $this->assertEquals(0, $exitCode);

        $discount = Discount::where('user_id', 69696)->where('table', 'VA-220')->first();

        $this->assertInstanceOf(Discount::class, $discount);
    }

    /**
     * Test importing with missing fields in products.
     *
     * @return void
     */
    public function testInvalidProductsFile()
    {
        $contents = 'Someinvalidproduct;8716936000541;zw0042005.jpg;1;;0,01;21;8716936000008;Dyka;Dyka krimpmoffen;O ringen voor krimpmoffen;;;Dijka;;A. Vuil en hemelwater leidingsystemen;O ringen voor krimpmoffen';

        File::put(storage_path('import/products.csv'), $contents);

        $exitCode = Artisan::call('import:products');

        $this->assertEquals(2, $exitCode);

        $product = Product::where('name', 'Someinvalidproduct')->first();

        $this->assertEquals(null, $product);
    }

    /**
     * Test importing with missing fields in discounts.
     *
     * @return void
     */
    public function testInvalidDiscountsFile()
    {
        $contents = 'VA-220;69696;20125125;Grohe onderdelen;';

        File::put(storage_path('import/discounts.csv'), $contents);

        $exitCode = Artisan::call('import:discounts');

        $this->assertEquals(2, $exitCode);

        $discount = Discount::where('user_id', 69696)->where('table', 'VA-220')->first();

        $this->assertEquals(null, $discount);
    }
}

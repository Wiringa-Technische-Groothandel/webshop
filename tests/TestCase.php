<?php

use App\User;
use App\Company;
use App\Product;
use App\Discount;
use App\Description;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Create a test company.
     *
     * @param array $params
     *
     * @return App\Company
     */
    protected function createCompany($params = [])
    {
        $company = new Company();

        $company->login = isset($params['login']) ? $params['login'] : '12345';
        $company->company = isset($params['name']) ? $params['name'] : 'Test Company';
        $company->active = isset($params['active']) ? $params['active'] : true;

        $company->save();

        return $company;
    }

    /**
     * Create a test user.
     *
     * @param bool   $admin
     * @param bool   $manager
     * @param string $username
     * @param string $company
     *
     * @return App\User
     */
    protected function createUser($admin = false, $manager = true, $username = '12345', $company = '12345')
    {
        $user = new User();

        $user->username = $username;
        $user->company_id = $company;
        $user->email = 'Test@example.com';
        $user->manager = (int) $manager;
        $user->isAdmin = (int) $admin;
        $user->password = bcrypt('password');

        $user->save();

        return $user;
    }

    /**
     * Create some content to test with.
     */
    protected function createContent()
    {
        DB::table('text')->insert([
            'name'      => 'test.page',
            'title'     => 'Test page',
            'page'      => 'Test',
            'content'   => 'This is some freaking awesome content!',
        ]);
    }

    /**
     * Create a product for testing.
     *
     * @return App\Product
     */
    protected function createProduct()
    {
        $contents = 'Someproduct;NE;BDY;9999999;10900100;20020658;1;A;Stk;Stk;1;HG;1;Dyka;8716936000541;zw0042005.jpg;1;;0,02;21;8716936000008;Dyka;Dyka krimpmoffen;O ringen voor krimpmoffen;;;Dijka;;A. Vuil en hemelwater leidingsystemen;O ringen voor krimpmoffen';
        $data = str_getcsv($contents, ';');

        $product = new Product;

        $product->name = $data[0];
        $product->number = $data[3];
        $product->group = $data[4];
        $product->altNumber = $data[5];
        $product->stockCode = $data[7];
        $product->registered_per = $data[8];
        $product->packed_per = $data[9];
        $product->price_per = $data[10];
        $product->refactor = preg_replace("/\,/", '.', $data[12]);
        $product->supplier = $data[13];
        $product->ean = $data[14];
        $product->image = $data[15];
        $product->length = $data[17];
        $product->price = $data[18];
        $product->vat = $data[20];
        $product->brand = $data[21];
        $product->series = $data[22];
        $product->type = $data[23];
        $product->special_price = ($data[24] === '' ? '0.00' : preg_replace("/\,/", '.', $data[24]));
        $product->action_type = $data[25];
        $product->keywords = $data[26];
        $product->related_products = $data[27];
        $product->catalog_group = $data[28];
        $product->catalog_index = $data[29];

        $product->save();

        return $product;
    }

    /**
     * Create a product for testing.
     *
     * @return App\Discount
     */
    protected function createDiscount()
    {
        $discount = new Discount;

        $discount->table = 'VA-220';
        $discount->User_id = 12345;
        $discount->product = 9999999;
        $discount->start_date = '1-1-2008 0:00:00';
        $discount->end_date = '31-12-9999 0:00:00';
        $discount->discount = '0';
        $discount->group_desc = 'Onderdelen';
        $discount->product_desc = '';

        $discount->save();

        return $discount;
    }

    protected function createDescription()
    {
        $d = new Description;

        $d->product_id = 9999999;
        $d->value = 'This is a test description';

        $d->save();
    }
}

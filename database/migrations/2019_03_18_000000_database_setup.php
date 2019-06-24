<?php

use Symfony\Component\Console\Output\ConsoleOutput;
use WTG\Models\Role;
use WTG\Models\Block;
use WTG\Models\Address;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatabaseSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ini_set('memory_limit', '-1');

        Schema::create('import_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('value')->nullable();
        });

        \DB::table('import_data')->insert([
            ['key' => 'last_assortment_file'],
            ['key' => 'last_assortment_run_time']
        ]);

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sku');
            $table->string('supplier_code', 50)->nullable(true);
            $table->integer('group');
            $table->string('name', 75);
            $table->string('ean', 16);
            $table->string('sales_unit', 5);
            $table->string('packing_unit', 5);
            $table->unsignedDecimal('length', 10, 2);
            $table->unsignedDecimal('height', 10, 2);
            $table->unsignedDecimal('width', 10, 2);
            $table->unsignedDecimal('weight', 10, 2);
            $table->unsignedDecimal('vat', 10, 2);
            $table->boolean('discontinued')->default(false);
            $table->boolean('blocked')->default(false);
            $table->boolean('inactive')->default(false);
            $table->string('brand', 50);
            $table->string('series', 50);
            $table->string('type', 50);
            $table->string('keywords', 100)->nullable();
            $table->string('related', 85)->nullable();
            $table->string('stock_display', 5)->default('S');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('synchronized_at')->nullable();

            $table->unique([
                'sku',
                'sales_unit'
            ]);

            $table->index('sku');
            $table->index('group');
            $table->index('name');
            $table->index([
                'brand',
                'series',
                'type'
            ]);
        });

        Schema::table('seo_urls', function (Blueprint $table) {
            $table->integer('product_id', false, true)->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        if (! app()->environment('testing')) {
            if (Artisan::call('import:soap:products', [], app(ConsoleOutput::class)) !== 0) {
                throw new \Exception('Import failed');
            }
        }

        \DB::transaction(function () {
            $this->runMigrations();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('pack_products');
        Schema::dropIfExists('packs');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('descriptions');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('carousel');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('logs');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('import_data');
        Schema::dropIfExists('products');
    }

    private function runMigrations()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('title', 100);
            $table->text('content');
            $table->timestamps();
        });

        $this->createBlock('news', 'Nieuws', '<h3>Dit is het nieuws blok</h3>');
        $this->createBlock('about', 'Over ons', '<h3>Dit is het over ons blok</h3>');
        $this->createBlock('downloads.catalog', 'Catalogus', '<h3>Dit is het catalogus blok</h3>');
        $this->createBlock('downloads.flyers', 'Flyers', '<h3>Dit is het flyers blok</h3>');
        $this->createBlock('downloads.products', 'Artikelbestanden', '<h3>Dit is het artikelbestanden blok</h3>');

        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_number')->unique();
            $table->string('name', 255);
            $table->string('street', 100);
            $table->string('postcode', 10);
            $table->string('city', 100);
            $table->boolean('active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        if (! app()->environment('testing')) {
            // Convert the old companies to the new table structure
            $oldCompanies = \DB::connection('mysql-old')->table('companies')->get();
            foreach ($oldCompanies as $company) {
                echo "[CREATING COMPANY] {$company->login}" . PHP_EOL;

                \DB::table('companies')->insert([
                    'customer_number'   => $company->login,
                    'name'              => $company->company,
                    'street'            => $company->street,
                    'postcode'          => $company->postcode,
                    'city'              => $company->city,
                    'active'            => $company->active,
                    'created_at'        => $company->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $company->created_at,
                    'updated_at'        => $company->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $company->updated_at,
                ]);
            }
        }

        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->nullable(true);
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('name', 150);
            $table->string('street', 50);
            $table->string('postcode', 10);
            $table->string('city', 75);
            $table->string('phone', 15)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->timestamps();
        });

        Address::unguard();
        /** @var Address $address */
        $address = Address::create([
            'id' => Address::DEFAULT_ID,
            'name' => 'Afhalen op locatie',
            'street' => 'Bovenstreek 1',
            'postcode' => '9731 DH',
            'city' => 'Groningen',
        ]);
        Address::reguard();

        \DB::statement('UPDATE addresses SET id = ? WHERE id = ?', [
            Address::DEFAULT_ID, $address->getId()
        ]);

        if (! app()->environment('testing')) {
            $oldAddresses = \DB::connection('mysql-old')->table('addresses')->get();
            foreach ($oldAddresses as $address) {
                $company = \DB::table('companies')->where('customer_number', $address->User_id)->first();

                echo "[CREATING ADDRESS] {$company->customer_number} - {$address->id}" . PHP_EOL;

                if (!$company) {
                    throw new \Exception('No company found for address ' . $address->id);
                }

                \DB::table('addresses')->insert([
                    'company_id' => $company->id,
                    'name' => $address->name,
                    'street' => $address->street,
                    'postcode' => $address->postcode,
                    'city' => $address->city,
                    'phone' => $address->telephone,
                    'mobile' => $address->mobile,
                    'created_at' => $address->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $address->created_at,
                    'updated_at' => $address->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $address->updated_at,
                ]);
            }
        }

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level', false, true);
            $table->string('name');
            $table->timestamps();
        });

        $roles = [
            ['level' => Role::ROLE_USER, 'name' => 'user'],
            ['level' => Role::ROLE_MANAGER, 'name' => 'manager'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $userRoleId = Role::level(Role::ROLE_USER)->first()->getId();

        Schema::create('customers', function (Blueprint $table) use ($userRoleId) {
            $table->increments('id');
            $table->integer('company_id', false, true);
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('role_id', false, true)->default($userRoleId);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('username', 50);
            $table->string('password', 100);
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id', false, true);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('address_id', false, true)->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->string('contact_email')->nullable();
            $table->string('order_email')->nullable();
            $table->timestamps();
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id', false, true);
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->integer('address_id', false, true)->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('finished_at')->nullable();
        });

        Schema::create('quote_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id', false, true);
            $table->integer('quote_id', false, true);
            $table->float('qty', 5, 3);
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->integer('customer_id', false, true);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('product_id', false, true);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        if (! app()->environment('testing')) {
            // Convert the old customers to the new table structure
            $oldCustomers = \DB::connection('mysql-old')->table('users')->get();
            foreach ($oldCustomers as $customer) {
                $company = \DB::table('companies')->where('customer_number', $customer->company_id)->first();

                echo "[CREATING CUSTOMER] {$company->customer_number} - {$customer->id}" . PHP_EOL;

                if (!$company) {
                    throw new \Exception('No company found for customer ' . $customer->id);
                }

                if ($customer->manager === "1" || $customer->company_id === $customer->username) {
                    $role = \DB::table('roles')->where('level', Role::ROLE_MANAGER)->first();
                } else {
                    $role = \DB::table('roles')->where('level', Role::ROLE_USER)->first();
                }

                $customerId = \DB::table('customers')->insertGetId([
                    'company_id' => $company->id,
                    'role_id' => $role->id,
                    'username' => $customer->username,
                    'password' => $customer->password,
                    'active' => true,
                    'created_at' => $customer->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $customer->created_at,
                    'updated_at' => $customer->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $customer->updated_at,
                ]);

                echo "[CREATING CONTACT] {$company->customer_number} - {$customerId}" . PHP_EOL;

                \DB::table('contacts')->insert([
                    'customer_id' => $customerId,
                    'contact_email' => $customer->email,
                    'order_email' => $customer->email,
                    'created_at' => $customer->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $customer->created_at,
                    'updated_at' => $customer->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $customer->updated_at,
                ]);

                echo "[CREATING QUOTE] {$company->customer_number} - {$customerId}" . PHP_EOL;

                $quoteId = \DB::table('quotes')->insertGetId([
                    'customer_id' => $customerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $cartItems = unserialize($customer->cart);

                if (!$cartItems) {
                    continue;
                }

                foreach ($cartItems as $cartItem) {
                    echo "[CREATING QUOTE ITEM] {$company->customer_number} - {$customerId} - {$quoteId}" . PHP_EOL;

                    $product = \DB::table('products')->where('sku', $cartItem[ 'id' ])->first(['id']);

                    if (!$product) {
                        echo "[SKIPPING NON-EXISTENT PRODUCT FOR QUOTE ITEM] {$company->customer_number} - {$customerId} - {$quoteId} - {$cartItem['id']}" . PHP_EOL;

                        continue;
                    }

                    \DB::table('quote_items')->insert([
                        'quote_id' => $quoteId,
                        'product_id' => $product->id,
                        'qty' => $cartItem[ 'qty' ],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $favorites = unserialize($customer->favorites);

                foreach ($favorites as $favorite) {
                    echo "[CREATING FAVORITE] {$company->customer_number} - {$customerId} - {$favorite}" . PHP_EOL;

                    $product = \DB::table('products')->where('sku', $favorite)->first(['id']);

                    if (!$product) {
                        echo "[SKIPPING NON-EXISTENT PRODUCT FOR FAVORITE] {$company->customer_number} - {$customerId} - {$quoteId} - {$favorite}" . PHP_EOL;

                        continue;
                    }

                    \DB::table('favorites')->insert([
                        'customer_id' => $customerId,
                        'product_id' => $product->id
                    ]);
                }
            }
        }

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true);
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('customer_number');
            $table->string('name');
            $table->string('street');
            $table->string('postcode');
            $table->string('city');
            $table->string('comment', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id', false, true);
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('name');
            $table->string('sku');
            $table->decimal('price', 10, 2);
            $table->float('qty', 8, 3);
            $table->decimal('subtotal', 10, 2);
        });

        if (! app()->environment('testing')) {
            $oldOrders = \DB::connection('mysql-old')->table('orders')->get();
            foreach ($oldOrders as $order) {
                $company = \DB::table('companies')->where('customer_number', $order->User_id)->first();

                echo "[CREATING ORDER] {$company->customer_number} - {$order->id}" . PHP_EOL;

                if ($company->customer_number == "99999") {
                    echo "[SKIPPING ORDER FOR TEST ACCOUNT]" . PHP_EOL;

                    continue;
                }

                if (!$company) {
                    throw new \Exception('No company found for order ' . $order->id);
                }

                if ($order->addressId === -1) {
                    $address = \DB::table('addresses')->where('id', 0)->first();
                } else {
                    $address = \DB::connection('mysql-old')->table('addresses')->where('id', $order->addressId)->first();
                }

                $items = unserialize($order->products);

                $orderId = \DB::table('orders')->insertGetId([
                    'company_id' => $company->id,
                    'customer_number' => $company->customer_number,
                    'name' => $address->name ?? '- Verwijderd adres -',
                    'street' => $address->street ?? '',
                    'postcode' => $address->postcode ?? '',
                    'city' => $address->city ?? '',
                    'comment' => $order->comment,
                    'created_at' => $order->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $order->created_at,
                    'updated_at' => $order->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $order->updated_at,
                ]);

                foreach ($items as $item) {
                    echo "[ADDING ORDER ITEM] {$company->customer_number} - {$orderId} - {$item['id']}" . PHP_EOL;

                    try {
                        \DB::table('order_items')->insert([
                            'order_id' => $orderId,
                            'name' => $item[ 'name' ],
                            'sku' => $item[ 'id' ],
                            'price' => $item[ 'price' ] ?? 0,
                            'qty' => $item[ 'qty' ],
                            'subtotal' => $item[ 'subtotal' ] ?? 0,
                        ]);
                    } catch (\Exception $e) {
                        echo "[SKIPPING INCOMPLETE ORDER ITEM] {$company->customer_number} - {$orderId} - {$item['id']}" . PHP_EOL;
                    }
                }
            }
        }

        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
            $table->json('context')->nullable();
            $table->integer('level', false, true);
            $table->string('level_name', 30)->nullable();
            $table->timestamp('logged_at');
            $table->json('extra')->nullable();
        });

        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true)->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->integer('importance');
            $table->string('product', 10)->nullable();
            $table->decimal('discount');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('group_desc')->nullable();
            $table->string('product_desc')->nullable();
            $table->string('index');
            $table->string('hash');
            $table->timestamp('imported_at');
            $table->timestamps();
        });

        Schema::create('carousel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('caption');
            $table->string('image');
            $table->integer('order', false, true);
            $table->timestamps();
        });

        if (! app()->environment('testing')) {
            $oldCarousel = \DB::connection('mysql-old')->table('carousel')->get();
            foreach ($oldCarousel as $carouselItem) {
                echo "[CREATING CAROUSEL ITEM] {$carouselItem->Image}" . PHP_EOL;

                \DB::table('carousel')->insert([
                    'title' => $carouselItem->Title,
                    'caption' => $carouselItem->Caption,
                    'image' => $carouselItem->Image,
                    'order' => $carouselItem->Order,
                    'created_at' => $carouselItem->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $carouselItem->created_at,
                    'updated_at' => $carouselItem->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $carouselItem->updated_at,
                ]);
            }
        }

        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('contact-company');
            $table->string('contact-name');
            $table->string('contact-address');
            $table->string('contact-city');
            $table->string('contact-postcode');
            $table->string('contact-phone-company');
            $table->string('contact-phone')->nullable();
            $table->string('contact-email');
            $table->string('contact-website')->nullable();

            $table->boolean('copy-contact')->default(false);
            $table->string('business-address');
            $table->string('business-city');
            $table->string('business-postcode');
            $table->string('business-phone');

            $table->string('payment-iban');
            $table->string('payment-kvk');
            $table->string('payment-vat');

            $table->string('other-alt-email')->nullable();
            $table->boolean('other-order-confirmation');
            $table->boolean('other-mail-productfile');

            $table->timestamps();
        });

        Schema::create('descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id', false, true);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->text('value');
        });

        if (! app()->environment('testing')) {
            $oldDescriptions = \DB::connection('mysql-old')->table('descriptions')->get();
            foreach ($oldDescriptions as $description) {
                echo "[CREATING PRODUCT DESCRIPTION] {$description->product_id}" . PHP_EOL;

                $product = \DB::table('products')->where('sku', $description->product_id)->first(['id']);

                if (!$product) {
                    echo "[SKIPPING NON-EXISTENT PRODUCT FOR DESCRIPTION] {$description->product_id}" . PHP_EOL;

                    continue;
                }

                \DB::table('descriptions')->insert([
                    'product_id' => $product->id,
                    'value' => $description->value
                ]);
            }
        }

        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('packs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id', false, true);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('pack_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id', false, true);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('pack_id', false, true);
            $table->foreign('pack_id')->references('id')->on('packs')->onDelete('cascade');
            $table->integer('amount', false, true);
            $table->timestamps();
        });

        if (! app()->environment('testing')) {
            $oldPacks = \DB::connection('mysql-old')->table('packs')->get();
            foreach ($oldPacks as $pack) {
                echo "[CREATING PACK] {$pack->product_number}" . PHP_EOL;

                $product = \DB::table('products')->where('sku', $pack->product_number)->first(['id']);

                if (!$product) {
                    echo "[SKIPPING NON-EXISTENT PRODUCT FOR PACK] {$pack->product_number}" . PHP_EOL;

                    continue;
                }

                $packId = \DB::table('packs')->insertGetId([
                    'product_id' => $product->id,
                    'created_at' => $pack->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $pack->created_at,
                    'updated_at' => $pack->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $pack->updated_at,
                ]);

                $oldPackProducts = \DB::connection('mysql-old')->table('pack_products')->where('pack_id', $pack->id)->get();

                foreach ($oldPackProducts as $packProduct) {
                    echo "[CREATING PACK PRODUCT] {$packId} - {$pack->id} - $packProduct->product" . PHP_EOL;

                    $product = \DB::table('products')->where('sku', $packProduct->product)->first(['id']);

                    if (!$product) {
                        echo "[SKIPPING NON-EXISTENT PRODUCT FOR PACK PRODUCT] {$pack->product_number} - {$packProduct->product}" . PHP_EOL;

                        continue;
                    }

                    \DB::table('pack_products')->insertGetId([
                        'product_id' => $product->id,
                        'pack_id' => $packId,
                        'amount' => $packProduct->amount,
                        'created_at' => $pack->created_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $pack->created_at,
                        'updated_at' => $pack->updated_at === '0000-00-00 00:00:00' ? '1970-01-01 01:00:00' : $pack->updated_at,
                    ]);
                }
            }
        }

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Create a new block.
     *
     * @param  string  $name
     * @param  string  $title
     * @param  string  $content
     * @return Block
     */
    private function createBlock($name, $title, $content): Block
    {
        $block = new Block;

        $block->setAttribute('name', $name);
        $block->setAttribute('title', $title);
        $block->setAttribute('content', $content);

        $block->save();

        return $block;
    }
}

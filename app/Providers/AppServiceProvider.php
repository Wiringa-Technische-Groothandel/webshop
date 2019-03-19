<?php

namespace WTG\Providers;

use WTG\Contracts\Models\AddressContract;
use WTG\Contracts\Models\PackProductContract;
use WTG\Contracts\Services\CompanyServiceContract;
use WTG\Models\Address;
use WTG\Models\Admin;
use WTG\Models\Block;
use WTG\Models\Order;
use WTG\Models\Pack;
use WTG\Models\PackProduct;
use WTG\Models\Quote;
use WTG\Models\Company;
use WTG\Models\Contact;
use WTG\Models\Customer;
use WTG\Models\OrderItem;
use WTG\Models\QuoteItem;
use WTG\Models\Description;
use WTG\Services\CartService;
use League\Flysystem\Filesystem;
use WTG\Services\CheckoutService;
use WTG\Services\CompanyService;
use WTG\Services\FavoritesService;
use WTG\Services\RecaptchaService;
use WTG\Soap\Service as SoapService;
use League\Flysystem\Sftp\SftpAdapter;
use WTG\Contracts\Models\PackContract;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\OrderContract;
use Illuminate\Support\ServiceProvider;
use WTG\Contracts\Models\AdminContract;
use WTG\Contracts\Models\BlockContract;
use WTG\Services\Account\AddressService;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\ContactContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\OrderItemContract;
use WTG\Contracts\Models\DescriptionContract;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Contracts\Services\CheckoutServiceContract;
use WTG\Contracts\Services\FavoritesServiceContract;
use WTG\Contracts\Services\Account\AddressServiceContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('sftp', function ($app, $config) {
            $adapter = new SftpAdapter($config);

            return new Filesystem($adapter);
        });

        view()->composer('*', function ($view) {
            if (auth('web')->check()) {
                /** @var CustomerContract $customer */
                $customer = auth('web')->user();

                $view->with('cart', app()->make(CartContract::class)->loadForCustomer($customer));
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias(RecaptchaService::class, 'captcha');

        // Model bindings
        $this->app->bind(PackContract::class, Pack::class);
        $this->app->bind(CartContract::class, Quote::class);
        $this->app->bind(AdminContract::class, Admin::class);
        $this->app->bind(BlockContract::class, Block::class);
        $this->app->bind(OrderContract::class, Order::class);
        $this->app->bind(AddressContract::class, Address::class);
        $this->app->bind(CompanyContract::class, Company::class);
        $this->app->bind(ContactContract::class, Contact::class);
        $this->app->bind(CustomerContract::class, Customer::class);
        $this->app->bind(CartItemContract::class, QuoteItem::class);
        $this->app->bind(OrderItemContract::class, OrderItem::class);
        $this->app->bind(DescriptionContract::class, Description::class);
        $this->app->bind(PackProductContract::class, PackProduct::class);

        // Service bindings
        $this->app->bind(CartServiceContract::class, CartService::class);
        $this->app->bind(CompanyServiceContract::class, CompanyService::class);
        $this->app->bind(AddressServiceContract::class, AddressService::class);
        $this->app->bind(CheckoutServiceContract::class, CheckoutService::class);
        $this->app->bind(FavoritesServiceContract::class, FavoritesService::class);

        $this->app->singleton(CartContract::class, function () {
            return new Quote();
        });

        $this->app->singleton('soap', SoapService::class);

        $this->app->when(SoapService::class)
            ->needs(\SoapClient::class)
            ->give(function () {
                return new \SoapClient(config('soap.wsdl'), [
                    'exceptions' => false
                ]);
            });
    }
}

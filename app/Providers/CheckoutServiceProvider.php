<?php

namespace WTG\Providers;

use WTG\Models\Order;
use WTG\Models\Quote;
use WTG\Models\OrderItem;
use WTG\Models\QuoteItem;
use WTG\Services\CartService;
use WTG\Services\CheckoutService;
use Illuminate\Support\Facades\View;
use WTG\Contracts\Models\CartContract;
use WTG\Contracts\Models\OrderContract;
use Illuminate\Support\ServiceProvider;
use WTG\Contracts\Models\CartItemContract;
use WTG\Contracts\Models\CustomerContract;
use WTG\Contracts\Models\OrderItemContract;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Contracts\Services\CheckoutServiceContract;

/**
 * Checkout service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * @var
     */
    protected $quote;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(OrderContract::class, Order::class);
        $this->app->bind(OrderItemContract::class, OrderItem::class);
        $this->app->bind(CartContract::class, Quote::class);
        $this->app->bind(CartItemContract::class, QuoteItem::class);
        $this->app->bind(CartServiceContract::class, CartService::class);
        $this->app->bind(CheckoutServiceContract::class, CheckoutService::class);

        $this->app->singleton(CartContract::class, function () {
            return new Quote();
        });

        View::composer('*', function ($view) {
            if (auth()->check()) {
                /** @var CustomerContract $customer */
                $customer = auth()->user();

                $view->with('cart', app()->make(CartContract::class)->loadForCustomer($customer));
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
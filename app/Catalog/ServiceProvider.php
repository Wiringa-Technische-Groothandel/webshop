<?php

declare(strict_types=1);

namespace WTG\Catalog;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use WTG\Catalog\Api\Model\ProductInterface;
use WTG\Catalog\Model\Product;

/**
 * Catalog service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ProductInterface::class, Product::class);
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

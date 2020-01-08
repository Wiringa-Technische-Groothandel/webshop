<?php

declare(strict_types=1);

namespace WTG\Providers;

use Illuminate\Support\ServiceProvider;
use WTG\Contracts\Models\ProductContract;
use WTG\Catalog\Model\Product;

/**
 * Catalog service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CatalogServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ProductContract::class, Product::class);
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

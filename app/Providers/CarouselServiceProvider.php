<?php

namespace WTG\Providers;

use WTG\Models\Carousel;
use WTG\Services\CarouselService;
use Illuminate\Support\ServiceProvider;
use WTG\Contracts\Models\CarouselContract;
use WTG\Contracts\Services\CarouselServiceContract;

/**
 * Carousel service provider.
 *
 * @package     WTG
 * @subpackage  Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CarouselServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CarouselContract::class, Carousel::class);
        $this->app->bind(CarouselServiceContract::class, CarouselService::class);
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
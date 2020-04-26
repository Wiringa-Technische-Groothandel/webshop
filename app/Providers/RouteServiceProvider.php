<?php

declare(strict_types=1);

namespace WTG\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

/**
 * Route service provider.
 *
 * @package     WTG\Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $webNamespace = 'WTG\Http\Controllers\Web';

    /**
     * @var string
     */
    protected $adminNamespace = 'WTG\Http\Controllers\Admin';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
//        $this->mapWebRoutes();
//        $this->mapAdminRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web', 'active'])
            ->namespace($this->webNamespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::namespace($this->adminNamespace)
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group(
            [
                'prefix' => 'rest/v1',
                'as' => 'api.',
                'middleware' => 'api'
            ],
            base_path('routes/api.php')
        );
    }
}

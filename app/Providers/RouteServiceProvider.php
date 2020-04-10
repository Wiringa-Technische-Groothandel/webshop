<?php

declare(strict_types=1);

namespace WTG\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
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
    protected string $apiNamespace = 'WTG\Http\Controllers\Api';

    /**
     * @var string
     */
    protected string $webNamespace = 'WTG\Http\Controllers\Web';

    /**
     * @var string
     */
    protected string $adminNamespace = 'WTG\Http\Controllers\Admin';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        $this->mapApiRoutes();

        $this->mapAdminRoutes();
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
     * Define the "api" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware(['api', 'active'])
            ->namespace($this->apiNamespace)
            ->group(base_path('routes/api.php'));
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
}

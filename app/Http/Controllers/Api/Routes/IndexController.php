<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Routes;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Controller;

/**
 * Rest api routes list controller.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var Router
     */
    protected Router $router;

    /**
     * IndexController constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $routes = $this->router->getRoutes()->getRoutesByName();

        return response()->json(
            collect($routes)->map(fn (Route $route) => $route->uri())
        );
    }
}

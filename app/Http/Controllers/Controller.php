<?php

declare(strict_types=1);

namespace WTG\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        return app()->call(static::class . '@execute');
    }

    /**
     * @return Response
     */
    abstract public function execute(): Response;
}

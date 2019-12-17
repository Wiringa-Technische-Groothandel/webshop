<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract admin controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class Controller extends BaseController
{
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

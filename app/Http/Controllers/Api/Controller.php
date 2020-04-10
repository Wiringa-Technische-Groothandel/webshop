<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract api controller.
 *
 * @package     WTG\Http
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

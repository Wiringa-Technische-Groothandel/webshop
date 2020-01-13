<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Logs;

use Symfony\Component\HttpFoundation\Response;
use WTG\Foundation\Logging\LogManager;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Admin logs index controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * IndexController constructor.
     *
     * @param LogManager $logManager
     */
    public function __construct(LogManager $logManager)
    {
        $this->logManager = $logManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            [
                'message' => '',
                'logs'    => $this->logManager->getSortedLogs(),
            ]
        );
    }
}

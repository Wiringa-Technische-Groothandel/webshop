<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Logs;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\LogManager;

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
     * @var Request
     */
    protected Request $request;

    /**
     * IndexController constructor.
     *
     * @param LogManager $logManager
     * @param Request    $request
     */
    public function __construct(LogManager $logManager, Request $request)
    {
        $this->logManager = $logManager;
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            [
                'message' => '',
                'logs'    => $this->logManager->getSortedLogs(
                    (int) $this->request->input('page', 1),
                    (int) $this->request->input('limit', 20)
                ),
            ]
        );
    }
}

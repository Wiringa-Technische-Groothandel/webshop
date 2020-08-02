<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Logs;

use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\LogManager;
use WTG\Models\Admin;

/**
 * Admin logs delete controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DeleteController extends Controller
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * @var AuthManager
     */
    protected AuthManager $authManager;

    /**
     * IndexController constructor.
     *
     * @param LogManager $logManager
     * @param AuthManager $authManager
     */
    public function __construct(LogManager $logManager, AuthManager $authManager)
    {
        $this->logManager = $logManager;
        $this->authManager = $authManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        /** @var Admin $user */
        $user = $this->authManager->guard('admin')->user();

        $this->logManager->truncateLogTable();
        $this->logManager->info(sprintf("Logs table cleaned by admin '%s'", $user->getUsername()));

        return response()->json(
            [
                'message' => __("De logs tabel is geleegd."),
                'success' => true,
            ]
        );
    }
}

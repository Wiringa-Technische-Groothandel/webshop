<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Auth;

use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\AdminAuthManager;

/**
 * Admin logout controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Auth
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class LogoutController extends Controller
{
    /**
     * @var AdminAuthManager
     */
    protected AdminAuthManager $authService;

    /**
     * LogoutController constructor.
     *
     * @param AdminAuthManager $authService
     */
    public function __construct(AdminAuthManager $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $this->authService->logout();

        return response()->json(
            [
                'message' => '',
                'success' => true,
            ]
        );
    }
}

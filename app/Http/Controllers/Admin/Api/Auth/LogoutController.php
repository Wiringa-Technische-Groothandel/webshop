<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Auth;

use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Services\Admin\AuthService;

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
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * LogoutController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
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

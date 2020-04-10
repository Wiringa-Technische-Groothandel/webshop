<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Auth;

use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Services\Admin\AuthService;

/**
 * Admin me controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Auth
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class MeController extends Controller
{
    /**
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * MeController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return RedirectResponse
     */
    public function execute(): Response
    {
        return response()->json(
            $this->authService->getUser()
        );
    }
}

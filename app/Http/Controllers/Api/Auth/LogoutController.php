<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Contracts\Services\AuthServiceInterface;
use WTG\Http\Controllers\Api\Controller;

/**
 * Api logout controller.
 *
 * @package     WTG\Http\Controllers\Api\Auth
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class LogoutController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var AuthServiceInterface
     */
    protected AuthServiceInterface $authService;

    /**
     * LoginController constructor.
     *
     * @param Request $request
     * @param AuthServiceInterface $authService
     */
    public function __construct(Request $request, AuthServiceInterface $authService)
    {
        $this->request = $request;
        $this->authService = $authService;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            $this->authService->logout()
        );
    }
}

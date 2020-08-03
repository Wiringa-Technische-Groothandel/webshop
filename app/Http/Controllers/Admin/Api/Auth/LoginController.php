<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Auth;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\AdminAuthManager;

/**
 * Admin login controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Auth
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class LoginController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var AdminAuthManager
     */
    protected AdminAuthManager $authService;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * LoginController constructor.
     *
     * @param Request $request
     * @param AdminAuthManager $authService
     * @param LogManager $logManager
     */
    public function __construct(Request $request, AdminAuthManager $authService, LogManager $logManager)
    {
        $this->request = $request;
        $this->authService = $authService;
        $this->logManager = $logManager;
    }

    /**
     * @return RedirectResponse
     */
    public function execute(): Response
    {
        try {
            $token = $this->authService->authenticate(
                $this->request->input('username'),
                $this->request->input('password')
            );
        } catch (AuthenticationException $exception) {
            return response()->json(
                [
                    'message' => $exception->getMessage(),
                    'success' => false,
                ]
            );
        } catch (Throwable $throwable) {
            $this->logManager->error($throwable);

            if ($this->authService->isLoggedIn()) {
                $this->authService->logout();
            }

            return response()->json(
                [
                    'message' => __('Er is een fout opgetreden tijdens het inloggen.'),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'token'      => $token ?? null,
                'token_type' => 'bearer',
                'expires_at' => $this->authService->getExpiryTime(),
                'message'    => '',
                'success'    => true,
            ]
        );
    }
}

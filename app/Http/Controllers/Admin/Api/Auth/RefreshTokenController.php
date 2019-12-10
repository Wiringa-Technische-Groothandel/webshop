<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Auth;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;

use Symfony\Component\HttpFoundation\Response;

use Throwable;

use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Services\Admin\AuthService;

/**
 * Admin refresh token controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Auth
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RefreshTokenController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * LoginController constructor.
     *
     * @param Request $request
     * @param AuthService $authService
     * @param LogManager $logManager
     */
    public function __construct(Request $request, AuthService $authService, LogManager $logManager)
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
            $token = $this->authService->refresh();
        } catch (AuthenticationException $exception) {
            return response()->json(
                [
                    'message' => $exception->getMessage(),
                    'success' => false,
                ]
            );
        } catch (TokenBlacklistedException $exception) {
            return response()->json(
                [
                    'logout'  => true,
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
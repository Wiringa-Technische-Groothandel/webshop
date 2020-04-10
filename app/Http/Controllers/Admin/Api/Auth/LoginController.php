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
use WTG\Services\Admin\AuthService;

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
            $success = $this->authService->authenticate(
                $this->request->input('username'),
                $this->request->input('password')
            );
        } catch (AuthenticationException $exception) {
            return response()->json(
                [
                    'user'    => null,
                    'message' => $exception->getMessage(),
                    'success' => false,
                ],
                401
            );
        } catch (Throwable $throwable) {
            $this->logManager->error($throwable);

            if ($this->authService->isLoggedIn()) {
                $this->authService->logout();
            }

            return response()->json(
                [
                    'user'    => null,
                    'message' => app()->environment('local') ?
                        $throwable->getMessage() :
                        __('Er is een fout opgetreden tijdens het inloggen.'),
                    'success' => false,
                ],
                500
            );
        }

        return response()->json(
            [
                'user'       => $this->authService->getUser(),
                'message'    => '',
                'success'    => $success,
            ]
        );
    }
}

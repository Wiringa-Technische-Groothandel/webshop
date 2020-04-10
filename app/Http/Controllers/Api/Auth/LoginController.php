<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Contracts\Services\AuthServiceInterface;
use WTG\Http\Controllers\Api\Controller;

/**
 * Login controller.
 *
 * @package     WTG\Http\Controllers\Api\Auth
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class LoginController extends Controller
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
        $customer = $this->authService->authenticate(
            (string)$this->request->input('customerNumber'),
            (string)$this->request->input('username'),
            (string)$this->request->input('password'),
            $this->request->input('remember', false)
        );

        return response()->json(
            [
                'user' => $customer
            ]
        );
    }
}

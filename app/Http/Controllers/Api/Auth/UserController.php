<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Auth;

use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Controller;

/**
 * Rest api auth user controller.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @var AuthManager
     */
    protected AuthManager $authManager;

    /**
     * UserController constructor.
     *
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            $this->authManager->guard('api')->user()
        );
    }
}

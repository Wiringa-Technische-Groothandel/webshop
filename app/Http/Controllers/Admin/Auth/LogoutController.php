<?php

namespace WTG\Http\Controllers\Admin\Auth;

use WTG\Services\Admin\AuthService;
use Illuminate\Http\RedirectResponse;
use WTG\Http\Controllers\Admin\Controller;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\Admin\AuthServiceContract;

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
    protected $authService;

    /**
     * LogoutController constructor.
     *
     * @param  ViewFactory  $view
     * @param  AuthServiceContract  $authService
     */
    public function __construct(ViewFactory $view, AuthServiceContract $authService)
    {
        parent::__construct($view);

        $this->authService = $authService;
    }

    /**
     * Logout the current admin user.
     *
     * @return RedirectResponse
     */
    public function postAction(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('admin.auth.login');
    }
}
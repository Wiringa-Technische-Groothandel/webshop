<?php

namespace WTG\Http\Controllers\Admin\Auth;

use Illuminate\Contracts\View\View;
use WTG\Services\Admin\AuthService;
use Illuminate\Http\RedirectResponse;
use WTG\Http\Controllers\Admin\Controller;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Auth\AuthenticationException;
use WTG\Http\Requests\Admin\Auth\LoginRequest;
use WTG\Contracts\Services\Admin\AuthServiceContract;

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
     * @var AuthService
     */
    protected $authService;

    /**
     * LoginController constructor.
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
     * Admin login page.
     *
     * @return View
     */
    public function getAction(): View
    {
        return $this->view->make('pages.admin.login');
    }

    /**
     * Admin login handler.
     *
     * @param  LoginRequest  $request
     * @return RedirectResponse
     */
    public function postAction(LoginRequest $request): RedirectResponse
    {
        try {
            $this->authService->authenticate(
                $request->input('username'),
                $request->input('password')
            );
        } catch (AuthenticationException $e) {
            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->withErrors($e->getMessage());
        }

        return redirect()->intended(route('admin.dashboard'));
    }
}
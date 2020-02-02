<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;

/**
 * Logout controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class LogoutController extends Controller
{
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Attempt to logout.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postAction(Request $request)
    {
        auth()->logout();

        $request->session()->regenerate(true);

        return redirect(route('home'));
    }
}

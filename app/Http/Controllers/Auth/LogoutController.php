<?php

namespace WTG\Http\Controllers\Auth;

use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

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
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function postAction(Request $request)
    {
        auth()->logout();

        $request->session()->regenerate(true);

        return redirect(route('home'));
    }
}

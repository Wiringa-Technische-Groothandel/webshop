<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Log;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Services\AuthManagerContract as AuthService;
use WTG\Http\Controllers\Controller;

/**
 * Login controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class LoginController extends Controller
{
    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Request handler.
     *
     * @return View
     */
    public function getAction(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Attempt to login.
     *
     * @param Request $request
     * @param AuthService $authService
     * @return RedirectResponse
     */
    public function postAction(Request $request, AuthService $authService): RedirectResponse
    {
        $customer = $authService->authenticateByRequest($request);

        if (! $customer) {
            return $this->failedAuthentication();
        }

        return $this->successAuthentication($customer->getCompany());
    }

    /**
     * Login failed handler.
     *
     * @return RedirectResponse
     */
    protected function failedAuthentication()
    {
        Log::info("[Login] Customer '" . request('company') . "' - '" . request('username') . "' failed to login");

        return back()
            ->withErrors(trans("auth.login.failed"));
    }

    /**
     * Login success handler.
     *
     * @param CompanyContract $company
     * @return RedirectResponse
     */
    protected function successAuthentication(CompanyContract $company)
    {
        Log::info("[Login] Customer '" . request('company') . "' - '" . request('username') . "' has logged in");

        return redirect(request('toUrl') ?: route('home'))
            ->with('status', trans('auth.login.success', ['name' => $company->getName()]));
    }
}

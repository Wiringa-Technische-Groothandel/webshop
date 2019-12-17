<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Auth;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use WTG\Contracts\Models\CompanyContract;
use WTG\Models\Company;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    use SendsPasswordResetEmails;
    use ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the password reset request form.
     *
     * @return Factory|View
     */
    public function showLinkRequestForm()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        /** @var Company $company */
        $company = app()->make(CompanyContract::class)
            ->where('customer_number', $request->input('company'))
            ->first();

        $response = $this->broker()->sendResetLink(
            [
                'company_id' => $company->getId(),
                'username'   => $request->input('username'),
            ]
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param Request $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate(
            $request,
            [
                'company'  => 'required',
                'username' => 'required',
            ]
        );
    }
}

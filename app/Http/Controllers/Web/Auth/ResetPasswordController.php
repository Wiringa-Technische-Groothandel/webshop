<?php

namespace WTG\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use WTG\Contracts\Models\CompanyContract;
use WTG\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Foundation\Auth\ResetsPasswords;
use WTG\Models\Company;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * ResetPasswordController constructor.
     *
     * @param  ViewFactory  $view
     */
    public function __construct(ViewFactory $view)
    {
        parent::__construct($view);

        $this->middleware('guest');
    }

    /**
     * Show the reset form.
     *
     * @param  Request  $request
     * @param  null|string  $token
     * @return \Illuminate\Contracts\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return $this->view->make('pages.auth.reset-password')->with(
            ['token' => $token, 'email' => $request->input('email')]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        /** @var Company $company */
        $company = app()->make(CompanyContract::class)
            ->where('customer_number', $request->input('company'))
            ->first();

        $credentials = $request->only('password', 'password_confirmation', 'token', 'username');

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset($credentials + [
            'company_id' => $company->getId(),
        ], function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token'     => 'required',
            'username'  => 'required',
            'company'   => 'required',
            'password'  => 'required|confirmed|min:6',
        ];
    }
}

<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Auth;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Models\CompanyContract;
use WTG\Http\Controllers\Controller;
use WTG\Models\Company;

/**
 * Reset password controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected string $redirectTo = '/';

    /**
     * ResetPasswordController constructor.
     *
     * @param ViewFactory $view
     */
    public function __construct(ViewFactory $view)
    {
        parent::__construct($view);

        $this->middleware('guest');
    }

    /**
     * Show the reset form.
     *
     * @param Request $request
     * @param null|string $token
     * @return View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return $this->view->make('pages.auth.reset-password')->with(
            ['token' => $token, 'email' => $request->input('email')]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        /** @var Company $company */
        $company = app()->make(CompanyContract::class)
            ->where('customer_number', $request->input('company'))
            ->first();

        if (! $company) {
            return $this->sendResetFailedResponse($request, Password::INVALID_USER);
        }

        $credentials = $request->only('password', 'password_confirmation', 'token', 'username');

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $credentials + [
                'company_id' => $company->getId(),
            ],
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
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
            'token'    => 'required',
            'username' => 'required',
            'company'  => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }
}

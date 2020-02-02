<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use WTG\Foundation\Logging\LogManager;
use WTG\Http\Controllers\Controller;
use WTG\Models\Customer;

/**
 * Password controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PasswordController extends Controller
{
    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * PasswordController constructor.
     *
     * @param ViewFactory $view
     * @param LogManager $logManager
     */
    public function __construct(ViewFactory $view, LogManager $logManager)
    {
        parent::__construct($view);

        $this->logManager = $logManager;
    }

    /**
     * Change password page.
     *
     * @return View
     */
    public function getAction()
    {
        return view('pages.account.change-password');
    }

    /**
     * Change the password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postAction(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password_old' => 'required',
                'password'     => 'required|confirmed',
            ]
        );

        /** @var Customer $user */
        $user = auth()->user();

        $user_details = [
            'username'   => $user->getAttribute('username'),
            'company_id' => $user->getAttribute('company_id'),
            'password'   => $request->input('password_old'),
        ];

        if ($validator->passes()) {
            if (auth()->validate($user_details)) {
                $user->setAttribute('password', bcrypt($request->input('password')));
                $user->save();

                $this->logManager->info(
                    sprintf("[Password change] User with id '%s' changed their password.", $user->getAttribute('id'))
                );

                return redirect()
                    ->back()
                    ->with('status', __("Uw wachtwoord is gewijzigd"));
            } else {
                $this->logManager->warning(
                    sprintf(
                        "[Password change] User with id '%s' failed to change their password. Reason: %s",
                        $user->getAttribute('id'),
                        __("Credential validation failed")
                    )
                );

                return redirect()
                    ->back()
                    ->withErrors(__("Het oude wachtwoord en uw huidige wachtwoord komen niet overeen."));
            }
        } else {
            $this->logManager->warning(
                sprintf(
                    "[Password change] User with id '%s' failed to change their password. Reason: %s",
                    $user->getAttribute('id'),
                    $validator->errors()
                )
            );

            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }
}

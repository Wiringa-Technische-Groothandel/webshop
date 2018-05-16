<?php

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;

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
     * Change password page.
     *
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        return view('pages.account.change-password');
    }

    /**
     * Change the password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAction(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'password_old' => 'required',
            'password'     => 'required|confirmed',
        ]);

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

                \Log::info("[Password change] User with id '{$user->getAttribute('id')}' changed their password.");

                return redirect()
                    ->back()
                    ->with('status', 'Uw wachtwoord is gewijzigd');
            } else {
                \Log::warning("[Password change] User with id '{$user->getAttribute('id')}' failed to change their password. Reason: Credential validation failed");

                return redirect()
                    ->back()
                    ->withErrors('Het oude wachtwoord en uw huidige wachtwoord komen niet overeen.');
            }
        } else {
            \Log::warning("[Password change] User with id '{$user->getAttribute('id')}' failed to change their password. Reason: ".$validator->errors());

            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }
}

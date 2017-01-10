<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class PasswordController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PasswordController extends Controller
{
    /**
     * Change password page.
     *
     * @return \Illuminate\View\View
     */
    public function getChangePassword()
    {
        return view('account.changePass');
    }

    /**
     * Change the password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doChangePassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'password_old' => 'required',
            'password'     => 'required|confirmed',
        ]);

        $user_details = [
            'username'   => \Auth::user()->username,
            'company_id' => \Auth::user()->company_id,
            'password'   => $request->input('password_old'),
        ];

        if ($validator->passes()) {
            if (\Auth::validate($user_details)) {
                $user = \Auth::user();
                $user->password = bcrypt($request->input('password'));
                $user->save();

                \Log::info("User with id '".$user->id."' changed their password.");

                return redirect('account')
                    ->with('status', 'Uw wachtwoord is gewijzigd');
            } else {
                \Log::warning("User with id '".\Auth::user()->id."' failed to change their password. Reason: Old password did not match");

                return redirect()
                    ->back()
                    ->withErrors('Het oude wachtwoord en uw huidige wachtwoord komen niet overeen.');
            }
        } else {
            \Log::warning("User with id '".\Auth::user()->id."' failed to change their password. Reason: ".$validator->errors());

            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }
}

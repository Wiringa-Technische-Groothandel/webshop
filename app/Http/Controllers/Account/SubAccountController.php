<?php

namespace App\Http\Controllers\Account;

use Auth;
use App\User;
use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class SubAccountController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SubAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('manager');
    }

    /**
     * Get the sub accounts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('account.sub_accounts', [
            'accounts' => Auth::user()->subAccounts(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|confirmed',
            'email'    => 'required|email',
        ]);

        if ($validator->passes()) {
            if (User::whereUsername($request->input('username'))->whereCompanyId(Auth::user()->company_id)->count()) {
                return redirect()
                    ->back()
                    ->withInput($request->except(['password', 'password_confirmation']))
                    ->withErrors('Er bestaat al een sub account met deze login naam.');
            }

            $user = new User();

            $user->username = $request->input('username');
            $user->company_id = Auth::user()->company_id;
            $user->email = $request->input('email');
            $user->isAdmin = false;
            $user->manager = ($request->input('manager') ? true : false);
            $user->password = bcrypt($request->input('password'));

            if ($user->save()) {
                \Log::info('Created a sub account for user '.Auth::user()->username);

                return redirect('account/accounts')
                    ->with('status', 'Het sub account is aangemaakt.');
            } else {
                \Log::error('An error occurred while creating a sub account');

                return redirect()
                    ->back()
                    ->withErrors('Er is een fout opgetreden tijdens het opslaan van het sub account');
            }
        } else {
            \Log::warning('Error occurred while validating input for sub account creation');

            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * Delete a sub account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'delete'   => 'required',
            'username' => 'required',
        ]);

        if ($validator->passes()) {
            $user = User::whereUsername($request->input('username'))
                ->where('company_id', Auth::user()->company_id)
                ->first();

            if ($user) {
                if ($user->isMain()) {
                    \Log::warning('User: '.Auth::id().' tried to delete their main account');

                    return redirect()
                        ->back()
                        ->withErrors('U kunt het hoofdaccount niet verwijderen');
                }

                if (Auth::user()->username === $user->username) {
                    \Log::warning('User: '.Auth::id().' tried to delete their own account');

                    return redirect()
                        ->back()
                        ->withErrors('U kunt uw eigen account niet verwijderen!');
                } else {
                    $user->delete();

                    \Log::info('User: '.Auth::id().' deleted a sub account');

                    return redirect()
                        ->back()
                        ->with('status', 'Het sub account is verwijderd');
                }
            } else {
                \Log::warning('User: '.Auth::id().' tried to delete a sub account that does not belong to them');

                return redirect()
                    ->back()
                    ->withErrors('Geen sub account gevonden die bij uw account hoort');
            }
        } else {
            \Log::warning('Failed to update sub account. Errors: '.json_encode($validator->errors()));

            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->withErrors($validator->errors());
        }
    }

    /**
     * Toggle manager status for a sub account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $user = User::findOrFail($id);

        if ($user->company_id === Auth::user()->company_id) {
            // Turn false into true and vice versa
            $user->manager = ($user->manager ? false : true);
            $user->save();

            return Response::json([
                'message' => 'Toggled manager status',
            ]);
        } else {
            return Response::json([
                'message' => 'The user with the given id does not belong to your account',
            ], 403);
        }
    }
}

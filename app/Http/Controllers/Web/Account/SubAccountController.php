<?php

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Company;
use WTG\Models\Contact;
use WTG\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\CreateAccountRequest;

/**
 * Sub account controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SubAccountController extends Controller
{
    /**
     * SubAccountController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:subaccounts-view');
    }

    /**
     * Main sub accounts page
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $accounts = $customer->getCompany()->getAttribute('customers');

        return view('pages.account.sub-accounts', compact('accounts'));
    }

    /**
     * Add a new account.
     *
     * @param  CreateAccountRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putAction(CreateAccountRequest $request)
    {
        try {
            \DB::beginTransaction();

            /** @var Customer $customer */
            $customer = $request->user();
            /** @var Company $company */
            $company = $customer->getAttribute('company');

            $usernameExists = $customer
                ->getCompany()
                ->getCustomers()
                ->where('username', $request->input('username'))
                ->exists();

            if ($usernameExists) {
                return back()
                    ->withInput($request->except(['password', 'password_confirmation']))
                    ->withErrors(
                        __("Er is al een account gekoppeld aan uw debiteur nummer met de zelfde gebruikersnaam.")
                    );
            }

            $account = new Customer;

            $account->setAttribute('company_id', $company->getAttribute('id'));
            $account->setAttribute('username', $request->input('username'));
            $account->setAttribute('password', bcrypt($request->input('password')));

            if (! $account->save()) {
                return $this->createAccountFailed($request);
            }

            $contact = new Contact;

            $contact->setAttribute('customer_id', $account->getAttribute('id'));
            $contact->setAttribute('contact_email', $request->input('email'));

            if (! $contact->save()) {
                return $this->createAccountFailed($request);
            }

            $account->assignRole($request->input('role'));

            \DB::commit();
        } catch (\Exception $e) {
            return $this->createAccountFailed($request);
        }

        return back()
            ->with('status', __("Het account is succesvol aangemaakt."));
    }

    /**
     * Account creation failed response.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createAccountFailed(Request $request)
    {
        \DB::rollBack();

        return back()
            ->withInput($request->except(['password', 'password_confirmation']))
            ->withErrors(__("Er is een fout opgetreden tijdens het opslaan van het account."));
    }

    // TODO: Make the stuff below work

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

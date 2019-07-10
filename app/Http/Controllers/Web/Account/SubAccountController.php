<?php

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Company;
use WTG\Models\Contact;
use WTG\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\CreateAccountRequest;
use WTG\Models\Role;

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
        $accounts = $customer->getCompany()->getCustomers();

        return view('pages.account.sub-accounts', compact('accounts'));
    }

    /**
     * Update a sub account.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAction(Request $request)
    {
        try {
            \DB::beginTransaction();

            /** @var Customer $customer */
            $customer = $request->user();
            /** @var Company $company */
            $company = $customer->getAttribute('company');
            /** @var Customer $account */
            $account = $company
                ->getCustomers()
                ->where('id', $request->input('account'))
                ->first();

            /** @var Role $role */
            $role = Role::level((int) $request->input('role'))->firstOrFail();

            if (! $account) {
                \DB::rollBack();

                return response()->json([
                    'message' => __('Dit sub-account behoort niet bij het hoofdaccount')
                ], 403);
            }

            $account->setRole($role);
            $account->save();

            \DB::commit();
        } catch (\Exception $e) {
            logger()->warning($e->getMessage());

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => __("De rol van de gebruiker is aangepast.")
        ]);
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
            /** @var Role $role */
            $role = Role::level((int) $request->input('role'))->firstOrFail();

            $usernameExists = $customer
                ->getCompany()
                ->getCustomers()
                ->where('username', $request->input('username'))
                ->isNotEmpty();

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

            $account->setRole($role);

            if (! $account->save()) {
                return $this->createAccountFailed($request);
            }

            $contact = new Contact;

            $contact->setAttribute('customer_id', $account->getAttribute('id'));
            $contact->setAttribute('contact_email', $request->input('email'));

            if (! $contact->save()) {
                return $this->createAccountFailed($request);
            }

            \DB::commit();
        } catch (\Exception $e) {
            return $this->createAccountFailed($request);
        }

        return back()
            ->with('status', __("Het account is succesvol aangemaakt."));
    }

    public function deleteAction(Request $request, string $id)
    {
        try {
            \DB::beginTransaction();

            /** @var Customer $customer */
            $customer = $request->user();

            /** @var Company $company */
            $company = $customer->getCompany();

            /** @var Customer $account */
            $account = $company
                ->getCustomers()
                ->where('id', $id)
                ->first();

            if (! $account) {
                \DB::rollBack();

                return response()->json([
                    'message' => __('Geen account gevonden met ID :id', ['id' => $id])
                ], 404);
            }

            $account->delete();

            \DB::commit();
        } catch (\Exception $e) {
            logger()->warning($e->getMessage());

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => __("Het account is verwijderd.")
        ]);
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
}

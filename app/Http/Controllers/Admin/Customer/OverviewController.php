<?php

namespace WTG\Http\Controllers\Admin\Customer;

use Illuminate\View\View;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Customer overview controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OverviewController extends Controller
{

    /**
     * The user management page.
     *
     * @return View
     */
    public function getAction(): View
    {
        return view('admin.customer.index');
    }

    /**
     * Get some user details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        if ($request->has('id')) {
            $company = Company::with('mainUser')->where('login', $request->input('id'))->first();

            if ($company !== null) {
                return response()->json([
                    'message' => 'User details for user '.$company->login,
                    'payload' => $company,
                ]);
            } else {
                return response()->json([
                    'message' => 'No user found with login '.$request->input('id'),
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Missing request parameter: `id`',
            ], 400);
        }
    }

    /**
     * Show the user added page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function added()
    {
        if (\Session::has('password') && \Session::has('input')) {
            return view('admin.user.added')
                ->with([
                    'password' => \Session::pull('password'),
                    'input' => \Session::get('input'),
                ]);
        } else {
            return redirect()
                ->route('admin.user::manager');
        }
    }

    /**
     * Add/update a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'company_id'   => 'required|integer|between:10000,99999',
            'company_name' => 'required|string',

            'address'  => 'required',
            'postcode' => 'required',
            'city'     => 'required',

            'email'  => 'required|email',
            'active' => 'required',
        ]);

        if ($validator->passes()) {
            if ($request->get('delete') === '') {
                // Get the company
                $company = Company::whereLogin($request->input('company_id'))->first();

                if ($company) {
                    // Remove associated users
                    $company->users->each(function ($user) {
                        $user->delete();
                    });

                    // Remove the company
                    $company->delete();

                    return redirect()
                        ->back()
                        ->with('status', 'Het bedrijf en bijbehorende gegevens zijn verwijderd');
                } else {
                    return redirect()
                        ->back()
                        ->withInput($request->input())
                        ->withErrors('Geen bedrijf gevonden met login naam '.$request->input('company_id'));
                }
            } elseif ($request->get('update') === '') {
                if ($company = Company::whereLogin($request->input('company_id'))->first()) {
                    $company->login = $request->input('company_id');
                    $company->company = $request->input('company_name');
                    $company->street = $request->input('address');
                    $company->postcode = $request->input('postcode');
                    $company->city = $request->input('city');
                    $company->active = $request->input('active');

                    $company->save();

                    \Log::info('Company '.$company->login.' has been updated by an admin');

                    $user = $company->mainUser;

                    $user->username = $request->input('company_id');
                    $user->company_id = $request->input('company_id');
                    $user->email = $request->input('email');

                    $user->save();

                    \Log::info('User '.$user->username.' has been updated by an admin');

                    return redirect()
                        ->back()
                        ->with('status', 'Bedrijf '.$company->company_id.' is aangepast');
                } else {
                    $pass = mt_rand(100000, 999999);

                    $company = new Company();

                    $company->login = $request->input('company_id');
                    $company->company = $request->input('company_name');
                    $company->street = $request->input('address');
                    $company->postcode = $request->input('postcode');
                    $company->city = $request->input('city');
                    $company->active = $request->input('active');

                    $company->save();

                    $user = new User();

                    $user->username = $request->input('company_id');
                    $user->company_id = $request->input('company_id');
                    $user->email = $request->input('email');
                    $user->manager = true;
                    $user->password = bcrypt($pass);

                    $user->save();

                    \Session::flash('password', $pass);
                    \Session::flash('input', $request->all());

                    return redirect()
                        ->route('admin.user::added');
                }
            } else {
                return redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors('Geen actie opgegeven (update of verwijderen)');
            }
        } else {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($validator->errors());
        }
    }

}
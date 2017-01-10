<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Cart;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class UserController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class UserController extends Controller
{
    /**
     * The user will be redirected to the previous page with
     * a message indicating whether the login was successful or not.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'company'  => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        // Is all the data entered
        if ($validator->passes()) {
            $user_data = [
                'company_id' => $request->input('company'),
                'username'   => $request->input('username'),
                'password'   => $request->input('password'),
            ];

            // Try to log the user in
            if (Auth::attempt($user_data, ($request->input('remember_me') === 'on' ? true : false))) {
                Log::info("[Login] User: [{$request->input('username')}] - Company [{$request->input('company')}] - success");

                if (Auth::user()->cart) {
                    Log::info("Loading shopping cart from database for user [{$request->input('username')}]");

                    foreach (unserialize(Auth::user()->cart) as $item) {
                        // Check if the id is set to prevent problems when reloading the cart
                        if (isset($item['id'])) {
                            // Restore the user's cart
                            Cart::add($item);
                        }
                    }
                }

                return redirect()
                    ->back()
                    ->with('status', 'U bent nu ingelogd');
            }
        }

        Log::info("[Login] User: [{$request->input('username')}] - Company [{$request->input('company')}] - failed");

        // The input field(s) is/are empty, go back to the previous page with an error message
        return redirect()
            ->back()
            ->withErrors('Gebruikersnaam en/of wachtwoord onjuist')
            ->withInput($request->except('password'));
    }

    /**
     * The user will be redirected to the main page if the logout was successful.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $user = Auth::user();

        Cart::destroy();
        Auth::logout();

        Log::info("[Logout] User: [{$user->username}] - Company [{$user->company_id}] - success");

        return redirect()
            ->intended('/')
            ->with('status', 'U bent nu uitgelogd');
    }

    /**
     * Show the registration page.
     *
     * @return \Illuminate\View\View
     */
    public function register()
    {
        $data = Session::has('registrationData') ? Session::get('registrationData') : [];

        return view('webshop.register', $data);
    }

    /**
     * Verify the registration page.
     * TODO: make this code prettier.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register_check(Request $request)
    {
        $data = $request->input();

        if ($request->has('corSite')) {
            $data['corSite'] = (strpos($data['corSite'], 'http') === 0 ? '' : 'http://').$data['corSite'];
        }

        $validator = \Validator::make($data, [
            'corContactName' => 'required',
            'corName'        => 'required',
            'corAddress'     => 'required',
            'corPostcode'    => 'required',
            'corCity'        => 'required',
            'corPhone'       => 'required',
            'corEmail'       => 'required|email',
            'corSite'        => 'url',

            'delAddress'  => 'required',
            'delPostcode' => 'required',
            'delCity'     => 'required',
            'delPhone'    => 'required',

            'betIBAN' => 'required',
            'betKvK'  => 'required',
            'betBTW'  => 'required',

            'digFactuur' => 'email',
        ]);

        if (! $validator->fails()) {
            $data['corContactName'] = $request->input('corContactName');
            $data['corName'] = $request->input('corName');
            $data['corAddress'] = $request->input('corAddress');
            $data['corPostcode'] = $request->input('corPostcode');
            $data['corCity'] = $request->input('corCity');
            $data['corContactPhone'] = $request->input('corContactPhone');
            $data['corPhone'] = $request->input('corPhone');
            $data['corFax'] = ($request->input('corFax') !== false ? $request->input('corFax') : '');
            $data['corEmail'] = $request->input('corEmail');
            $data['corSite'] = ($request->input('corSite') !== false ? $request->input('corSite') : '');

            $data['corIsDel'] = $request->input('corIsDel');

            $data['delAddress'] = ($data['corIsDel'] ? $data['corAddress'] : $request->input('delAddress'));
            $data['delPostcode'] = ($data['corIsDel'] ? $data['corPostcode'] : $request->input('delPostcode'));
            $data['delCity'] = ($data['corIsDel'] ? $data['corCity'] : $request->input('delCity'));
            $data['delPhone'] = ($data['corIsDel'] ? $data['corPhone'] : $request->input('delPhone'));
            $data['delFax'] = ($data['corIsDel'] ? $data['corFax'] : ($request->input('delFax') !== false ? $request->input('delFax') : ''));

            $data['betIBAN'] = $request->input('betIBAN');
            $data['betKvK'] = $request->input('betKvK');
            $data['betBTW'] = $request->input('betBTW');

            $data['digFactuur'] = $request->input('digFactuur');
            $data['digOrder'] = $request->input('digOrder');
            $data['digArtikel'] = $request->input('digArtikel');

            Session::flash('registrationData', $data);

            \DB::table('registrations')->insert([
                'company'    => $data['corName'],
                'formdata'   => json_encode($data),
                'created_at' => Carbon::now(),
            ]);

            \Mail::send('email.registration', $data, function ($message) {
                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                $message->to('verkoop@wiringa.nl');

                $message->subject('Webshop registratie');
            });

            return redirect('/register/sent');
        } else {
            return redirect()->back()
                ->withErrors($validator->errors())
                ->withInput($request->input());
        }
    }

    /**
     * Show the registration success page.
     *
     * @return mixed
     */
    public function registerSent()
    {
        if (! Session::has('registrationData')) {
            return redirect('/register');
        }

        return view('webshop.registerSent');
    }
}

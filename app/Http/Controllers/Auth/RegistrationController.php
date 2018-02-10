<?php

namespace WTG\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use WTG\Contracts\Services\RegistrationServiceContract;
use WTG\Models\Registration;

/**
 * Registration controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class RegistrationController extends Controller
{
    /**
     * @var RegistrationServiceContract
     */
    protected $registrationService;

    /**
     * RegistrationController constructor.
     *
     * @param  RegistrationServiceContract  $registrationService
     */
    public function __construct(RegistrationServiceContract $registrationService)
    {
        $this->registrationService = $registrationService;
    }


    /**
     * Registration page.
     *
     * @return View
     */
    public function getAction()
    {
        return view('pages.auth.registration');
    }

    /**
     * Save the registration form.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function putAction(Request $request): RedirectResponse
    {
        try {
            $this->registrationService->create($request->all());
        } catch (\Exception $e) {
            return back()
                ->withErrors($e->getMessage())
                ->withInput($request->input());
        }

        return redirect(route('home'))
            ->with('status', __('Uw registratie aanvraag is in goede orde ontvangen.'));
    }
}
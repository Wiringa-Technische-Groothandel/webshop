<?php

namespace WTG\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use WTG\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\RegistrationServiceContract;

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
     * @param  ViewFactory  $view
     * @param  RegistrationServiceContract  $registrationService
     */
    public function __construct(ViewFactory $view, RegistrationServiceContract $registrationService)
    {
        parent::__construct($view);

        $this->registrationService = $registrationService;
    }


    /**
     * Registration page.
     *
     * @return View
     */
    public function getAction(): View
    {
        return $this->view->make('pages.auth.registration');
    }

    /**
     * Save the registration form.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function putAction(Request $request): RedirectResponse
    {
        if (! app('captcha')->check((string) $request->input('g-recaptcha-response'))) {
            return back()
                ->withErrors(__('Vul aub de recaptcha in.'))
                ->withInput($request->input());
        }

        try {
            $this->registrationService->create($request->except('g-recaptcha-response'));
        } catch (\Exception $e) {
            return back()
                ->withErrors($e->getMessage())
                ->withInput($request->input());
        }

        return redirect(route('home'))
            ->with('status', __('Uw registratie aanvraag is in goede orde ontvangen.'));
    }
}
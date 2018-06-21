<?php

namespace WTG\Http\Controllers\Admin\Email;

use WTG\Mail\Test;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\RedirectResponse;
use WTG\Http\Controllers\Admin\Controller;
use Illuminate\View\Factory as ViewFactory;

/**
 * Admin email index controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Email
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * EmailController constructor.
     *
     * @param  ViewFactory  $view
     * @param  Mailer  $mailer
     */
    public function __construct(ViewFactory $view, Mailer $mailer)
    {
        parent::__construct($view);

        $this->mailer = $mailer;
    }

    /**
     * Email page.
     *
     * @return View
     */
    public function getAction(): View
    {
        return $this->view->make('pages.admin.email');
    }

    /**
     * Attempt to send a test email.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAction(Request $request): RedirectResponse
    {
        $validator = \Validator::make($request->input(), [
            'email' => 'required|email'
        ]);

        if ($validator->passes()) {
            $this->mailer->to($request->input('email'))->send(new Test);

            return redirect()
                ->back()
                ->with('status', 'De email is verzonden');
        }

        return redirect()
            ->back()
            ->withErrors($validator->errors());
    }
}
<?php

namespace WTG\Http\Controllers\Admin;

use WTG\Mail\Test;
use Illuminate\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\View\Factory as ViewFactory;

/**
 * Email controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class EmailController extends Controller
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
     * @return \Illuminate\Contracts\View\View
     */
    public function getAction(): View
    {
        return $this->view->make('pages.admin.email');
    }

    /**
     * Attempt to send a test email
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function test(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'email' => 'required|email'
        ]);

        if ($validator->passes()) {
            $this->mailer->to($request->input('email'))->send(new Test());

            return redirect()
                ->back()
                ->with('status', 'De email is verzonden');
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }
}
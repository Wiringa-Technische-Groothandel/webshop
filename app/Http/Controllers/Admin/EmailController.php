<?php

namespace WTG\Http\Controllers\Admin;

use GuzzleHttp\Client;
use Illuminate\Mail\Mailer;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\TransferException;
use WTG\Mail\Test;

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
     * @param  Mailer  $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Email page.
     *
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        return view('pages.admin.email', [
            'mailstats' => $this->stats(request())
        ]);
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

    /**
     * Get mailgun stats.
     *
     * @param  Request  $request
     * @return bool|string
     */
    public function stats(Request $request)
    {
        $duration = $request->has('period') ? $request->input('period') : '31d';

        $client = new Client([
            'base_uri' => 'https://api.mailgun.net/v3/',
            'auth' => ['api', config('services.mailgun.secret')]
        ]);

        try {
            $response = $client->get(config('services.mailgun.domain')."/stats/total", [
                'query' => [
                    'event' => array('accepted', 'delivered', 'failed'),
                    'duration' => $duration
                ]
            ]);
        } catch (TransferException $e) {
            \Log::error($e->getMessage());

            return false;
        }

        return $response->getBody()->getContents();
    }
}
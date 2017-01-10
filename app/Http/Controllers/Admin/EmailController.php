<?php

namespace App\Http\Controllers\Admin;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\TransferException;

/**
 * Class EmailController.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class EmailController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('admin.email.index', [
            'mailstats' => $this->stats(request()),
        ]);
    }

    /**
     * Attempt to send a test email.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function test(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'email' => 'required|email',
        ]);

        if ($validator->passes()) {
            $mail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $mail->send('email.test', [], function ($m) use ($request) {
                $m->subject('Test email');
                $m->to($request->input('email'));
            });

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
     * @return bool|string
     */
    public function stats(Request $request)
    {
        $duration = $request->has('period') ? $request->input('period') : '31d';

        $client = new Client([
            'base_uri' => 'https://api.mailgun.net/v3/',
            'auth' => ['api', config('services.mailgun.secret')],
        ]);

        try {
            $response = $client->get(config('services.mailgun.domain').'/stats/total', [
                'query' => [
                    'event' => ['accepted', 'delivered', 'failed'],
                    'duration' => $duration,
                ],
            ]);
        } catch (TransferException $e) {
            \Log::error($e->getMessage());

            return false;
        }

        return $response->getBody()->getContents();
    }
}

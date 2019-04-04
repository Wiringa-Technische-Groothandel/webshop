<?php

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Customer;
use WTG\Mail\DiscountFile;
use Illuminate\Mail\Mailer;
use Illuminate\Http\Request;
use WTG\Services\AuthService;
use Illuminate\Contracts\View\View;
use WTG\Http\Controllers\Controller;
use WTG\Services\DiscountFileService;
use WTG\Exceptions\InvalidFormatException;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Session\Store as SessionStore;
use WTG\Http\Requests\DownloadDiscountFileRequest;

/**
 * Discount controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DiscountController extends Controller
{
    const RECEIVE_METHOD_DOWNLOAD = 'download';
    const RECEIVE_METHOD_EMAIL = 'email';
    const DISCOUNT_FILE_NAME = 'icc_data.txt';

    /**
     * @var DiscountFileService
     */
    private $discountFileService;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var SessionStore
     */
    private $session;

    /**
     * DiscountController constructor.
     *
     * @param ViewFactory $view
     * @param DiscountFileService $discountFileService
     * @param Mailer $mailer
     * @param AuthService $authService
     * @param SessionStore $session
     */
    public function __construct(ViewFactory $view, DiscountFileService $discountFileService,
                                Mailer $mailer, AuthService $authService, SessionStore $session)
    {
        parent::__construct($view);

        $this->discountFileService = $discountFileService;
        $this->mailer = $mailer;
        $this->authService = $authService;
        $this->session = $session;
    }

    /**
     * Generator page
     *
     * @param  Request  $request
     * @return View
     */
    public function getAction(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        return $this->view->make('pages.account.discount', compact('customer'));
    }

    public function postAction(DownloadDiscountFileRequest $request)
    {
        try {
            $receiveMethod = $request->input('receive');
            $email = $this->authService->getCurrentCustomer()->getContact()->getContactEmail();

            if (! $email && $receiveMethod === self::RECEIVE_METHOD_EMAIL) {
                return back()->withErrors(__('U hebt geen contact e-mail adres ingesteld op uw account. De e-mail kan niet verzonden worden.'));
            }

            $discountData = $this->discountFileService->generateData(
                $request->input('format')
            );

            if ($receiveMethod === self::RECEIVE_METHOD_DOWNLOAD) {
                return response()->make($discountData, 200, [
                    'Content-type'        => 'text/plain',
                    'Content-Disposition' => 'attachment; filename="' . self::DISCOUNT_FILE_NAME . '"',
                ]);
            } elseif ($receiveMethod === self::RECEIVE_METHOD_EMAIL) {
                $this->mailer->to($email)->send(new DiscountFile($discountData));

                $this->session->flash('status', __('Het kortingsbestand is verzonden naar :email', ['email' => $email]));
            }
        } catch (InvalidFormatException $e) {
            return back()->withErrors(__("Ongeldig bestands formaat."));
        }

        return back();
    }

    /**
     * Generate the file
     *
     * @param  Request  $request
     * @param  string  $type
     * @param  string  $method
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generate(Request $request, $type, $method)
    {
        if ($type === 'icc') {
            $contents = app('discount_file')->icc();
            $filename = 'icc_data'.Auth::user()->company_id.'.txt';
        } elseif ($type === 'csv') {
            $contents = app('discount_file')->csv();
            $filename = 'icc_data'.Auth::user()->company_id.'.csv';
        } else {
            return redirect('account/discountfile')
                ->withErrors('Ongeldig bestands type.');
        }

        $filePath = storage_path('export/discounts/'.$filename);

        if (\File::exists($filePath)) {
            \File::delete($filePath);
        }

        \File::put($filePath, $contents);

        if ($method === 'download') {
            return \Response::download($filePath);
        } elseif ($method === 'mail') {
            \Mail::send('email.discountfile', [], function ($message) use ($filePath, $filename) {
                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');
                $message->to(Auth::user()->email);
                $message->subject('[WTG Webshop] Kortingsbestand');
                $message->attach($filePath, ['as' => $filename]);
            });

            return redirect()
                ->back()
                ->with('status', 'Het kortingsbestand is verzonden naar '.Auth::user()->email);
        } else {
            return redirect()
                ->back()
                ->withErrors('Geen verzendmethode opgegeven.');
        }
    }
}
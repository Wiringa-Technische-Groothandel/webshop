<?php

declare(strict_types=1);

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
    const DISCOUNT_FILE_NAME_FORMAT = 'icc_data%d.txt';

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
            $customer = $this->authService->getCurrentCustomer();
            $email = $customer->getContact()->getContactEmail();

            if (! $email && $receiveMethod === self::RECEIVE_METHOD_EMAIL) {
                return back()->withErrors(__('U hebt geen contact e-mail adres ingesteld op uw account. De e-mail kan niet verzonden worden.'));
            }

            $discountData = $this->discountFileService
                                 ->setCustomer($customer)
                                 ->generateData($request->input('format'));
            $fileName = sprintf(self::DISCOUNT_FILE_NAME_FORMAT, $customer->getCompany()->getCustomerNumber());

            if ($receiveMethod === self::RECEIVE_METHOD_DOWNLOAD) {
                return response()->make($discountData, 200, [
                    'Content-type'        => 'text/plain',
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                ]);
            } elseif ($receiveMethod === self::RECEIVE_METHOD_EMAIL) {
                $this->mailer->to($email)->send(new DiscountFile($discountData, $fileName));

                $this->session->flash('status', __('Het kortingsbestand is verzonden naar :email', ['email' => $email]));
            }
        } catch (InvalidFormatException $e) {
            return back()->withErrors(__("Ongeldig bestands formaat."));
        }

        return back();
    }
}

<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Customer;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface as Log;
use Illuminate\Contracts\View\View;
use WTG\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory as ViewFactory;
use WTG\Http\Requests\Account\Profile\UpdateRequest;

/**
 * Profile controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ProfileController extends Controller
{
    /**
     * @var Log
     */
    protected $log;

    /**
     * ProfileController constructor.
     *
     * @param  ViewFactory  $view
     * @param  Log  $log
     */
    public function __construct(ViewFactory $view, Log $log)
    {
        parent::__construct($view);

        $this->log = $log;
    }

    /**
     * The account dashboard.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function getAction(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $address = $customer->getContact()->getDefaultAddress();

        return $this->view->make('pages.account.profile', compact('customer', 'address'));
    }

    /**
     * The account dashboard.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function editAction(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();

        return $this->view->make('pages.account.edit-profile', compact('customer'));
    }

    /**
     * Update the customer profile.
     *
     * @param  UpdateRequest  $request
     * @return RedirectResponse
     */
    public function postAction(UpdateRequest $request): RedirectResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $contact = $customer->getContact();

        try {
            $contact->setContactEmail($request->input('contact-email'));
            $contact->setOrderEmail($request->input('order-email'));

            $contact->save();
        } catch (\Exception $e) {
            $this->log->error($e);

            return back()
                ->withInput($request->all())
                ->withErrors(__('Er is een fout opgetreden tijdens het opslaan van uw profiel'));
        }

        return redirect(route('account.profile'))
            ->with('status', __('Uw profiel is aangepast.'));
    }
}
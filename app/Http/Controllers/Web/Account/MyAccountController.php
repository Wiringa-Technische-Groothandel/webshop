<?php

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use WTG\Http\Controllers\Controller;

/**
 * My account controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class MyAccountController extends Controller
{
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

        return $this->view->make('pages.account.my-account', compact('customer', 'address'));
    }
}
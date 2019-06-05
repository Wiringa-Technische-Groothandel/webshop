<?php

namespace WTG\Http\Controllers\Web\Checkout;

use WTG\Models\Address;
use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Http\Requests\UpdateQuoteAddressRequest;
use WTG\Contracts\Services\Account\AddressServiceContract;

/**
 * Address controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class AddressController extends Controller
{
    /**
     * @var AddressServiceContract
     */
    protected $addressService;

    /**
     * @var CartServiceContract
     */
    protected $cartService;

    /**
     * AddressController constructor.
     *
     * @param  ViewFactory  $view
     * @param  AddressServiceContract  $addressService
     * @param  CartServiceContract  $cartService
     */
    public function __construct(ViewFactory $view, AddressServiceContract $addressService, CartServiceContract $cartService)
    {
        parent::__construct($view);

        $this->addressService = $addressService;
        $this->cartService = $cartService;
    }

    /**
     * Address selection page.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request)
    {
        if (! $this->cartService->getItemCount()) {
            return redirect(route('checkout.cart'));
        }

        /** @var Customer $customer */
        $customer = $request->user();
        $addresses = $this->addressService->getAddressesForCustomer($customer);
        $quoteAddress = $this->cartService->getDeliveryAddress();

        return view('pages.checkout.address', compact('customer', 'addresses', 'quoteAddress'));
    }

    /**
     * Change the delivery address of the quote.
     *
     * @param  UpdateQuoteAddressRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patchAction(UpdateQuoteAddressRequest $request)
    {
        $addressId = $request->input('addressId');
        /** @var Customer $customer */
        $customer = $request->user();

        if ($addressId === Address::DEFAULT_ID) {
            $address = $this->addressService->getDefaultAddress();
        } else {
            /** @var Address $address */
            $address = $this->addressService->getAddressForCustomerById($customer, $addressId);
        }

        if (!$address) {
            return response()->json([
                'message' => __('Het opgegeven adres is niet gevonden.'),
                'success' => false
            ], 400);
        }

        $isSuccess = $this->cartService->setDeliveryAddress($address);

        return response()->json([
            'message' => $isSuccess ? __('Het afleveradres is aangepast.') : __('Er is een fout opgetreden tijdens het aanpassen van het afleveradres.'),
            'address' => $address,
            'success' => $isSuccess
        ]);
    }
}
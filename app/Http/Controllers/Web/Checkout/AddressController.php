<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Checkout;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use WTG\Contracts\Services\Account\AddressServiceContract;
use WTG\Contracts\Services\CartServiceContract;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\UpdateQuoteAddressRequest;
use WTG\Models\Address;
use WTG\Models\Customer;

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
    protected AddressServiceContract $addressService;

    /**
     * @var CartServiceContract
     */
    protected CartServiceContract $cartService;

    /**
     * AddressController constructor.
     *
     * @param ViewFactory $view
     * @param AddressServiceContract $addressService
     * @param CartServiceContract $cartService
     */
    public function __construct(
        ViewFactory $view,
        AddressServiceContract $addressService,
        CartServiceContract $cartService
    ) {
        parent::__construct($view);

        $this->addressService = $addressService;
        $this->cartService = $cartService;
    }

    /**
     * Address selection page.
     *
     * @param Request $request
     * @return View
     */
    public function getAction(Request $request)
    {
        if (! $this->cartService->getItemCount()) {
            return redirect(route('checkout.cart'));
        }

        /** @var Customer $customer */
        $customer = $request->user();
        $addresses = $this->addressService->getAddressesForCustomer($customer, false);
        $defaultAddress = $this->addressService->getDefaultAddressForCustomer($customer);
        $pickupAddress = $this->addressService->getPickupAddress();
        $quoteAddress = $this->cartService->getDeliveryAddress();

        return view(
            'pages.checkout.address',
            compact('customer', 'addresses', 'quoteAddress', 'defaultAddress', 'pickupAddress')
        );
    }

    /**
     * Change the delivery address of the quote.
     *
     * @param UpdateQuoteAddressRequest $request
     * @return JsonResponse
     */
    public function patchAction(UpdateQuoteAddressRequest $request)
    {
        $addressId = $request->input('addressId');
        /** @var Customer $customer */
        $customer = $request->user();

        if ($addressId === Address::DEFAULT_ID) {
            $address = $this->addressService->getPickupAddress();
        } else {
            /** @var Address $address */
            $address = $this->addressService->getAddressForCustomerById($customer, (int)$addressId);
        }

        if (! $address) {
            return response()->json(
                [
                    'message' => __('Het opgegeven adres is niet gevonden.'),
                    'success' => false,
                    'address' => null,
                ],
                400
            );
        }

        $isSuccess = $this->cartService->setDeliveryAddress($address);

        return response()->json(
            [
                'message' => $isSuccess ? __('Het afleveradres is aangepast.') : __(
                    'Er is een fout opgetreden tijdens het aanpassen van het afleveradres.'
                ),
                'address' => $address,
                'success' => $isSuccess,
            ]
        );
    }
}

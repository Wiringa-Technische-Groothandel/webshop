<?php

namespace WTG\Http\Controllers\Account;

use WTG\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use WTG\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use WTG\Contracts\Services\AddressServiceContract;
use WTG\Http\Requests\Account\Address\CreateRequest;
use WTG\Http\Requests\Account\Address\DeleteRequest;
use WTG\Http\Requests\Account\Address\UpdateDefaultRequest;

/**
 * Address controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class AddressController extends Controller
{
    /**
     * @var AddressServiceContract
     */
    protected $addressService;

    /**
     * AddressController constructor.
     *
     * @param  AddressServiceContract  $addressService
     */
    public function __construct(AddressServiceContract $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * The address list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $addresses = $this->addressService->getAddressesForCustomer($customer);
        $defaultAddress = $this->addressService->getDefaultAddressIdForCustomer($customer);

        return view('pages.account.addresses', compact('customer', 'addresses', 'defaultAddress'));
    }

    /**
     * Create a new address.
     *
     * @param  CreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putAction(CreateRequest $request): RedirectResponse
    {
        if ($this->addressService->createFromRequest($request)) {
            return back()->with('status', __("Het adres is toegevoegd."));
        }

        return back()
            ->withErrors(__("Er is een fout opgetreden tijdens het opslaan van het adres."))
            ->withInput($request->all());
    }

    /**
     * Change the default address.
     *
     * @param  UpdateDefaultRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patchAction(UpdateDefaultRequest $request): JsonResponse
    {
        $isSuccess = $this->addressService->setDefaultForCustomer($request->user(), $request->input('address'));

        return response()->json([
            'success' => $isSuccess,
            'message' => $isSuccess ?
                __("Het standaard adres is aangepast.") :
                __("Er is een fout opgetreden tijdens het aanpassen van het adres."),
            'code' => 200
        ]);
    }

    /**
     * Remove an address.
     *
     * @param  DeleteRequest  $request
     * @param  string  $addressId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(DeleteRequest $request, string $addressId): RedirectResponse
    {
        if ($this->addressService->deleteForCustomer($request->user(), $addressId)) {
            return back()->with("status", __("Het adres is verwijderd."));
        } else {
            return back()
                ->withErrors(__("Er is een fout opgetreden tijdens het verwijderen van het adres."));
        }
    }
}
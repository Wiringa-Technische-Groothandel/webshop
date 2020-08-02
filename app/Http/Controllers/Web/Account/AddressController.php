<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\Account\AddressManagerContract;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\Account\Address\CreateRequest;
use WTG\Http\Requests\Account\Address\DeleteRequest;
use WTG\Http\Requests\Account\Address\UpdateDefaultRequest;
use WTG\Models\Customer;

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
     * @var AddressManagerContract
     */
    protected $addressService;

    /**
     * AddressController constructor.
     *
     * @param ViewFactory $view
     * @param AddressManagerContract $addressService
     */
    public function __construct(ViewFactory $view, AddressManagerContract $addressService)
    {
        parent::__construct($view);

        $this->addressService = $addressService;
    }

    /**
     * The address list.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request): View
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $addresses = $this->addressService->getAddressesForCustomer($customer, false);
        $defaultAddress = $this->addressService->getDefaultAddressIdForCustomer($customer);

        return $this->view->make('pages.account.addresses', compact('customer', 'addresses', 'defaultAddress'));
    }

    /**
     * Create a new address.
     *
     * @param CreateRequest $request
     * @return RedirectResponse
     */
    public function putAction(CreateRequest $request): RedirectResponse
    {
        $isSuccess = $this->addressService->createForCustomer(
            $request->user(),
            $request->input('name'),
            $request->input('address'),
            $request->input('postcode'),
            $request->input('city'),
            $request->input('phone', null),
            $request->input('mobile', null),
            $request->has('default')
        );

        if ($isSuccess) {
            return back()->with('status', __('Het adres is toegevoegd.'));
        }

        return back()
            ->withErrors(__('Er is een fout opgetreden tijdens het opslaan van het adres.'))
            ->withInput($request->all());
    }

    /**
     * Change the default address.
     *
     * @param UpdateDefaultRequest $request
     * @return JsonResponse
     */
    public function patchAction(UpdateDefaultRequest $request): JsonResponse
    {
        $isSuccess = $this->addressService->setDefaultForCustomer($request->user(), $request->input('address'));

        return response()->json(
            [
                'success' => $isSuccess,
                'message' => $isSuccess ?
                    __('Het standaard adres is aangepast.') :
                    __('Er is een fout opgetreden tijdens het aanpassen van het adres.'),
                'code'    => 200,
            ]
        );
    }

    /**
     * Remove an address.
     *
     * @param DeleteRequest $request
     * @param string $addressId
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(DeleteRequest $request, string $addressId): RedirectResponse
    {
        if ($this->addressService->deleteForCustomer($request->user(), $addressId)) {
            return back()->with('status', __('Het adres is verwijderd.'));
        } else {
            return back()
                ->withErrors(__('Er is een fout opgetreden tijdens het verwijderen van het adres.'));
        }
    }
}

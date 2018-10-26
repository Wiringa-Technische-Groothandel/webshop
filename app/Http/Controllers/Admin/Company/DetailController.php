<?php

namespace WTG\Http\Controllers\Admin\Company;

use WTG\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use WTG\Services\Checkout\OrderService;
use WTG\Contracts\Models\CompanyContract;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Detail controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Company
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DetailController extends Controller
{
    /**
     * Company detail page.
     *
     * @param  string  $id
     * @return View
     */
    public function getAction(string $id): View
    {
        $previousUrl = strpos(url()->previous(), '/admin/companies') !== false ?
            url()->previous() : route('admin.companies');

        $company = app()->make(CompanyContract::class)->with('customers')->findOrFail($id);
        $orders = app()->make(OrderService::class)->getForCompany($company)->groupBy(function ($order) {
            return $order->created_at->format('Y');
        })->map(function ($orders) {
            return $orders->count();
        })->sort(function ($count, $year) {
            return $year;
        });

        return $this->view->make('pages.admin.company', compact('company', 'orders', 'previousUrl'));
    }

    /**
     * Update a company details.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return RedirectResponse
     */
    public function postAction(Request $request, string $id): RedirectResponse
    {
        /** @var Company $company */
        $company = app()->make(CompanyContract::class)->findOrFail($id);

        if ($request->input('customer-number') !== $company->getCustomerNumber()) {
            $duplicate = app()->make(CompanyContract::class)
                ->where('customer_number', $request->input('customer-number'))
                ->exists();

            if ($duplicate) {
                return back()->withErrors(__('Er bestaat reeds een debiteur met nummer :number.', [
                    'number' => $request->input('customer-number')
                ]));
            }
        }

        $company->setName($request->input('name'));
        $company->setCustomerNumber($request->input('customer-number'));
        $company->setStreet($request->input('street'));
        $company->setCity($request->input('city'));
        $company->setPostcode($request->input('postcode'));
        $company->setActive((bool) $request->input('active'));

        try {
            $company->saveOrFail();
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());

            return back()->withErrors(__('Er is een fout opgetreden tijdens het opslaan van het bedrijf.'));
        }

        return back()->with('status', __('De wijzigingen zijn opgeslagen.'));
    }
}
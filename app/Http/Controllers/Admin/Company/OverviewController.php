<?php

namespace WTG\Http\Controllers\Admin\Company;

use Illuminate\Http\Request;
use WTG\Services\CompanyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use WTG\Contracts\Models\CompanyContract;
use WTG\Http\Controllers\Admin\Controller;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CompanyServiceContract;

/**
 * Company manager controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OverviewController extends Controller
{
    /**
     * @var CompanyService
     */
    protected $companyService;

    /**
     * OverviewController constructor.
     *
     * @param  ViewFactory  $view
     * @param  CompanyServiceContract  $companyService
     */
    public function __construct(ViewFactory $view, CompanyServiceContract $companyService)
    {
        parent::__construct($view);

        $this->companyService = $companyService;
    }

    /**
     * Company list.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getAction(): View
    {
        $companies = app()->make(CompanyContract::class)
            ->with('customers')
            ->orderBy('customer_number')
            ->paginate(10);

        return $this->view->make('pages.admin.companies', compact('companies'));
    }

    /**
     * Create a new company.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function putAction(Request $request): RedirectResponse
    {
        try {
            \DB::beginTransaction();

            $company = $this->companyService->createCompany($request->all([
                'name', 'customer-number', 'street', 'city', 'postcode', 'active'
            ]));

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();

            logger()->error($e->getMessage());
            session()->remove('new-customer-password');

            return back()->withInput($request->all())->withErrors($e->getMessage());
        }

        return redirect(route('admin.company.edit', [
            'company' => $company->getId()
        ]))->with('status', __('De debiteur is aangemaakt.'));
    }
}
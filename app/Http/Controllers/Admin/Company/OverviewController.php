<?php

namespace WTG\Http\Controllers\Admin\Company;

use Illuminate\Contracts\View\View;
use WTG\Contracts\Models\CompanyContract;
use WTG\Http\Controllers\Admin\Controller;

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
     * Company list.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getAction(): View
    {
        $companies = app()->make(CompanyContract::class)->with('customers')->orderBy('customer_number')->paginate(10);

        return $this->view->make('pages.admin.companies', compact('companies'));
    }
}
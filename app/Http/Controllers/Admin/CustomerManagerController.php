<?php

namespace WTG\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use WTG\Contracts\Models\CompanyContract;
use WTG\Contracts\Models\CustomerContract;

/**
 * Customer manager controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CustomerManagerController extends Controller
{
    /**
     * Customer list.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function getAction(string $id): View
    {
        $company = app()->make(CompanyContract::class)->findOrFail($id);
        $customers = app()->make(CustomerContract::class)->where('company_id', $id)->paginate(10);

        return $this->view->make('pages.admin.customer-manager', [
            'customers' => $customers,
            'company' => $company
        ]);
    }
}
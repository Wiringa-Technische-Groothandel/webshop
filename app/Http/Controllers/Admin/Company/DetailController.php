<?php

namespace WTG\Http\Controllers\Admin\Company;

use Illuminate\Contracts\View\View;
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
        $company = app()->make(CompanyContract::class)->with('customers')->findOrFail($id);

        return $this->view->make('pages.admin.company', compact('company'));
    }
}
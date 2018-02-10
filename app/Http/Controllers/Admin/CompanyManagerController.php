<?php

namespace WTG\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use WTG\Contracts\Models\CompanyContract;

/**
 * Company manager controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CompanyManagerController extends Controller
{
    /**
     * Company list.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getAction(): View
    {
        return $this->view->make('pages.admin.company-manager', [
            'companies' => app()->make(CompanyContract::class)->paginate(10)
        ]);
    }
}
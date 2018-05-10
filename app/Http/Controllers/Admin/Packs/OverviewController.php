<?php

namespace WTG\Http\Controllers\Admin\Packs;

use Illuminate\Contracts\View\View;
use WTG\Contracts\Models\PackContract;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Pack overview controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Packs
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class OverviewController extends Controller
{
    /**
     * Overview of special product packs.
     *
     * @return \Illuminate\View\View
     */
    public function getAction(): View
    {
        $packs = app()->make(PackContract::class)->all();

        return $this->view->make('pages.admin.packs', compact('packs'));
    }
}
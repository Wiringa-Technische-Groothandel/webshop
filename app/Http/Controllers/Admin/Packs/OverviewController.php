<?php

namespace WTG\Http\Controllers\Admin\Packs;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        /** @var Collection $packs */
        $packs = app(PackContract::class)->all();

        return $this->view->make('pages.admin.packs', compact('packs'));
    }

    /**
     * Delete a product pack.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $pack = app(PackContract::class)
            ->findOrFail($request->input('pack_id'));

        $pack->delete();

        return back()->with('status', __('Het actiepakket is verwijderd.'));
    }
}
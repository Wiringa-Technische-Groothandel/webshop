<?php

namespace WTG\Http\Controllers\Admin\Packs;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

use WTG\Contracts\Models\PackContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Pack;
use WTG\Models\Product;

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
    public function putAction(Request $request): RedirectResponse
    {
        $sku = $request->input('product');

        /** @var Product $product */
        $product = app(ProductContract::class)
            ->where('sku', $sku)
            ->first();

        if (! $product) {
            return back()->withErrors(__('Geen product gevonden met productnummer :sku', ['sku' => $sku]));
        }

        /** @var Pack $pack */
        $pack = app(PackContract::class);
        $pack->setProduct($product);
        $pack->save();

        return back()->with('status', __('Het actiepakket is aangemaakt.'));
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
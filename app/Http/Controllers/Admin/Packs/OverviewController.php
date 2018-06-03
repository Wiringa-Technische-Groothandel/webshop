<?php

namespace WTG\Http\Controllers\Admin\Packs;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $packs = app()->make(PackContract::class)->all();

        return $this->view->make('pages.admin.packs', compact('packs'));
    }

    /**
     * Create a pack.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function putAction(Request $request): RedirectResponse
    {
        /** @var Product $product */
        $product = app()->make(ProductContract::class)->where('sku', $request->input('product'))->firstOrFail();
        /** @var Pack $pack */
        $pack = app()->make(PackContract::class);
        $pack->setProduct($product);
        $pack->save();

        return back()->with('success', __('Het actiepakket is aangemaakt.'));
    }
}
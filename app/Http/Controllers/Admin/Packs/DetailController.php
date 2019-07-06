<?php

namespace WTG\Http\Controllers\Admin\Packs;

use WTG\Models\Pack;
use WTG\Models\Product;
use WTG\Models\PackProduct;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use WTG\Contracts\Models\PackContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Contracts\Models\PackProductContract;

class DetailController extends Controller
{
    /**
     * Edit pack page.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return View
     */
    public function getAction(Request $request, int $id): View
    {
        $pack = app()->make(PackContract::class);

        return $this->view->make('pages.admin.pack', [
            'pack' => $pack->findOrFail($id)
        ]);
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

        return back()->with('status', __('Het actiepakket is aangemaakt.'));
    }

    /**
     * Add a product to a pack.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function patchAction(Request $request, int $id): RedirectResponse
    {
        /** @var Product $product */
        $product = app()->make(ProductContract::class)
            ->where('sku', $request->input('product'))
            ->firstOrFail();

        /** @var Pack $pack */
        $pack = app()->make(PackContract::class)->findOrFail($id);

        /** @var PackProduct $packProduct */
        $packProduct = app()->make(PackProductContract::class)
            ->where('product_id', $product->getId())
            ->where('pack_id', $id)
            ->first();

        if ($packProduct === null) {
            $packProduct = app()->make(PackProductContract::class);
            $packProduct->setPack($pack);
            $packProduct->setProduct($product);
        }

        $packProduct->setAmount($request->input('amount'));
        $packProduct->save();

        return back()->with('status', __('Het product is :action.', ['action' => $packProduct->wasRecentlyCreated ? 'toegevoegd' : 'aangepast']));
    }

    /**
     * Remove a product from a pack.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, int $id): RedirectResponse
    {
        app(PackContract::class)->findOrFail($id);

        $itemId = $request->input('item_id');

        /** @var PackProduct $packProduct */
        $packProduct = app()->make(PackProductContract::class)
            ->where('id', $itemId)
            ->where('pack_id', $id)
            ->first();

        if ($packProduct === null) {
            return back()->withErrors(__('Geen item gevonden met id :id.', ['id' => $itemId]));
        }

        $packProduct->delete();

        return back()->with('status', __('Het product is :action.', [ 'action' => 'verwijderd' ]));
    }
}
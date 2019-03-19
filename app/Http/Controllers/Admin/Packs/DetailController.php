<?php

namespace WTG\Http\Controllers\Admin\Packs;

use Illuminate\Http\RedirectResponse;
use WTG\Models\Pack;
use WTG\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use WTG\Contracts\Models\PackContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Http\Controllers\Admin\Controller;

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

        return back()->with('success', __('Het actiepakket is aangemaakt.'));
    }
}
<?php

namespace WTG\Http\Controllers\Admin;

use App\Pack;
use App\PackProduct;
use App\Product;
use Illuminate\Http\Request;

/**
 * Class PacksController.
 *
 * @package WTG
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class PacksController extends Controller
{


    /**
     * Edit a special product pack.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        return view('admin/packs/edit', [
            'pack' => Pack::find($id),
        ]);
    }

    /**
     * Save the product pack to the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product'  => 'required',
        ]);

        if ($validator->passes()) {
            $pack = Pack::firstOrCreate([
                'product_number' => $request->get('product'),
            ]);

            return redirect()
                ->intended('admin/packs/edit/'.$pack->id)
                ->with('status', 'Actiepakket is aangemaakt');
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * Remove a product pack and the associated products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'pack' => 'required',
        ]);

        if ($validator->passes()) {
            $pack = $request->get('pack');

            if (Pack::where('id', $pack)->count() === 1) {
                PackProduct::where('pack_id', $pack)->delete();
                Pack::destroy($pack);

                return redirect()
                    ->back()
                    ->with('status', 'Het actiepakket is verwijderd');
            } else {
                return redirect()
                    ->back()
                    ->withErrors("Geen actiepakket gevonden met id: {$pack}");
            }
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * Add a product to a pack.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProduct(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product'   => 'required',
            'amount'    => 'required',
            'pack'      => 'required',
        ]);

        if ($validator->passes()) {
            if (Product::where('number', $request->get('product'))->count() === 1) {
                $pp = new PackProduct();

                $pp->pack_id = $request->get('pack');
                $pp->product = $request->get('product');
                $pp->amount = $request->get('amount');

                $pp->save();

                return redirect()
                    ->intended('admin/packs/edit/'.$request->get('pack'))
                    ->with('status', 'Product is toegevoegd');
            } else {
                return redirect()
                    ->back()
                    ->withErrors("Product {$request->get('product')} bestaat niet");
            }
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * Remove a product from a pack.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeProduct(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product'   => 'required',
            'pack'      => 'required',
        ]);

        if ($validator->passes()) {
            if (PackProduct::where('id', $request->get('product'))->where('pack_id', $request->get('pack'))->count() === 1) {
                PackProduct::destroy($request->get('product'));

                return redirect()
                    ->back()
                    ->with('status', 'Het product is verwijderd');
            } else {
                return redirect()
                    ->back()
                    ->withErrors('Het opgevraagde product behoort niet tot dit actiepakket');
            }
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }
}

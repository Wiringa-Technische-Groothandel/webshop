<?php

namespace App\Http\Controllers;

use Auth;
use Cart;
use Session;
use App\Pack;
use App\Order;
use App\Address;
use App\Product;
use Illuminate\Http\Request;

/**
 * Class CartController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CartController extends Controller
{
    /**
     * Show the cart.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('webshop.cart', [
            'cart'      => Cart::content(),
            'addresses' => Auth::user()->addresses,
        ]);
    }

    /**
     * Add a product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProduct(Request $request)
    {
        $number = $request->get('product');
        $qty = $request->get('qty');

        $validator = \Validator::make($request->all(), [
            'product'   => 'required|digits:7',
            'qty'       => 'required|numeric|min:1',
        ]);

        if (! $validator->fails()) {
            // Load the product data
            $product = Product::where('number', $number)->firstOrFail();
            // Load the user cart data
            $cartArray = unserialize(Auth::user()->cart);

            // Add the product data to the cart data
            $cartArray[$number] =
            $productData = [
                'id'      => $product->number,
                'name'    => $product->name,
                'qty'     => $qty,
                'price'   => $product->real_price,
                'options' => [
                    'special' => (bool) Pack::where('product_number', $product->number)->count(),
                    'korting' => $product->discount,
                ],
            ];

            // Add the product to the cart
            Cart::add($productData);

            // Save the updated array to the database
            $user = Auth::user();
            $user->cart = serialize($cartArray);
            $user->save();

            if (Session::has('continueShopping')) {
                return redirect(Session::get('continueShopping'))
                    ->with('status', "Het product {$number} is toegevoegd aan uw winkelwagen");
            } else {
                return redirect('cart');
            }
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * Modify or remove a product from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rowId = $request->input('rowId');
        $qty = $request->input('qty');
        $artNr = $request->input('artNr');

        $validator = \Validator::make($request->all(), [
            'rowId' => 'required',
            'qty'   => 'required|numeric|min:1',
            'artNr' => 'required',
        ]);

        if ($validator->passes()) {
            if ($request->input('edit') === '') {
                // Load the user cart data
                $cartArray = unserialize(Auth::user()->cart);

                $cartArray[$artNr]['qty'] = $qty;

                // Save the updated array to the database
                $user = Auth::user();
                $user->cart = serialize($cartArray);
                $user->save();

                Cart::update($rowId, [
                    'qty' => $qty,
                ]);

                return redirect('cart')
                    ->with('status', 'Uw winkelwagen is geupdatet');
            } elseif ($request->input('remove') === '') {
                // Load the user cart data
                $cartArray = unserialize(Auth::user()->cart);

                unset($cartArray[$artNr]);

                // Save the updated array to the database
                $user = Auth::user();
                $user->cart = serialize($cartArray);
                $user->save();

                Cart::remove($rowId);

                return redirect('cart')
                    ->with('status', 'Het product is verwijderd');
            } else {
                return redirect('cart')
                    ->withErrors('Er is een fout opgetreden');
            }
        } else {
            return redirect('cart')
                ->withErrors($validator->errors());
        }
    }

    /**
     * To destroy or not to destroy.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        if (! Cart::destroy()) {
            $user = Auth::user();
            $user->cart = null;
            $user->save();

            return redirect('/')
                ->with('status', 'Uw winkelwagen is geleegd');
        } else {
            return redirect('cart')
                ->withErrors('Er is een fout opgetreden tijden het legen van de winkelwagen');
        }
    }

    /**
     * Mail the order to the company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function order(Request $request)
    {
        if (Cart::count(false) !== 0) {
            if ($request->has('addressId')) {
                $addressId = $request->input('addressId');

                if ($request->input('addressId') === '-2') {
                    $address = new \stdClass();

                    $address->name = '';
                    $address->street = 'Wordt gehaald';
                    $address->postcode = '';
                    $address->city = '';
                    $address->telephone = '';
                    $address->mobile = '';
                } elseif (Address::where('id', $addressId)->where('User_id', Auth::user()->company_id)->first()) {
                    $address = Address::where('id', $addressId)
                        ->where('User_id', Auth::user()->company_id)
                        ->first();
                } else {
                    return redirect('cart')
                        ->withErrors('Het opgegeven adres hoort niet bij uw account');
                }

                $data['address'] = $address;
                $data['cart'] = Cart::content();
                $data['comment'] = $request->input('comment');

                \Mail::send('email.order', $data, function ($message) {
                    $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                    if (Auth::user()->company_id === '99999') {
                        $message->to('gfw@wiringa.nl');
                    } else {
                        $message->to('verkoop@wiringa.nl');
                    }

                    $message->subject('Webshop order');
                });

                $items = [];

                foreach (Cart::content() as $item) {
                    $items[] = [
                        'id'   => $item->id,
                        'name' => $item->name,
                        'qty'  => $item->qty,
                    ];
                }

                $order = new Order();

                $order->products = serialize($items);
                $order->User_id = Auth::user()->company_id;
                $order->comment = $data['comment'];
                $order->addressId = $addressId;

                $order->save();

                Session::flash('order', true);

                Cart::destroy();

                $user = Auth::user();
                $user->cart = 'a:0:{}';
                $user->save();

                return redirect('cart/order/finished');
            } else {
                return redirect('cart')
                    ->withErrors('Geen adres opgegeven');
            }
        } else {
            return redirect('/')
                ->withErrors('Er zitten geen producten in uw winkelwagen!');
        }
    }
}

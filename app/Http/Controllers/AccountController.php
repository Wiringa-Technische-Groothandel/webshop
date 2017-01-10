<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use File;
use Mail;
use Helper;
use Session;
use Response;
use App\Order;
use App\Address;
use App\Product;
use Illuminate\Http\Request;

/**
 * Class AccountController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class AccountController extends Controller
{
    /**
     * The overview for the account page.
     *
     * @return \Illuminate\View\View
     */
    public function overview()
    {
        $orderCount = Order::where('User_id', Auth::user()->company_id)->count();

        return view('account.overview', [
            'orderCount' => $orderCount,
        ]);
    }

    /**
     * This will fetch the favorites list from the database and
     * transform it into a list categorised by series.
     *
     * @return \Illuminate\View\View
     */
    public function favorites()
    {
        $favoritesArray = unserialize(Auth::user()->favorites);
        $seriesData = [];
        $productGroup = [];

        // Get the product data
        $productData = Product::whereIn('number', $favoritesArray)->get();

        // Store each series from the products in a separate array for categorisation
        foreach ($productData as $product) {
            array_push($seriesData, $product->series);
        }

        // Only keep the unique values
        $seriesData = array_unique($seriesData);

        // Put the product and series data in a new array
        foreach ($seriesData as $key => $series) {
            foreach ($productData as $product) {
                if ($product->series == $series) {
                    $productGroup[$series][] = $product;
                }
            }
        }

        return view('account.favorites', [
            'favorites'     => $productData,
            'discounts'     => Helper::getProductDiscount(Auth::user()->login),
            'groupData'     => $productGroup,
        ]);
    }

    /**
     * Update the favourites from a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function modFav(Request $request)
    {
        if ($request->ajax()) {
            $product = $request->input('product');

            $validator = \Validator::make(
                ['product' => $product],
                ['product' => 'required|digits:7']
            );

            if ($validator->passes()) {
                $currentFavorites = unserialize(Auth::user()->favorites);

                // Remove the product from the favorites if it is already in
                if (in_array($product, $currentFavorites)) {
                    $key = array_search($product, $currentFavorites);

                    // Remove the product from the favorites array
                    unset($currentFavorites[$key]);

                    // Save the new favorites array to the database
                    $user = Auth::user();
                    $user->favorites = serialize($currentFavorites);
                    $user->save();

                    echo 'SUCCESS';
                    exit();
                } else {
                    // Add the product to the favorites array
                    array_push($currentFavorites, $product);

                    // Save the new favorites array to the database
                    $user = Auth::user();
                    $user->favorites = serialize($currentFavorites);
                    $user->save();

                    echo 'SUCCESS';
                    exit();
                }
            } else {
                echo 'FAILED';
                exit();
            }
        } else {
            return redirect()
                ->back()
                ->withErrors('Geen toegang!');
        }
    }

    /**
     * Check if the product is in the favorites array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function isFav(Request $request)
    {
        if ($request->ajax()) {
            $product = $request->input('product');

            $validator = \Validator::make(
                ['product' => $product],
                ['product' => 'required|digits:7']
            );

            if ($validator->passes()) {
                $currentFavorites = unserialize(Auth::user()->favorites);

                if (in_array($product, $currentFavorites)) {
                    return 'IN_ARRAY';
                } else {
                    return 'NOT_IN_ARRAY';
                }
            } else {
                return 'FAILED';
            }
        } else {
            return redirect()
                ->back()
                ->withErrors('Geen toegang!');
        }
    }

    /**
     * This page will show the order history.
     *
     * @return \Illuminate\View\View
     */
    public function orderHistory()
    {
        $orderList = Order::where('User_id', Auth::user()->company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('account.orderhistory', [
            'orderlist' => $orderList,
        ]);
    }

    /**
     * The address list page.
     *
     * @return \Illuminate\View\View
     */
    public function addressList()
    {
        $addressList = Address::where('User_id', Auth::user()->company_id)->get();

        return view('account.addresslist', [
            'addresslist' => $addressList,
        ]);
    }

    /**
     * Handle the add address request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addAddress(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'          => 'required',
            'street'        => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'postcode'      => 'required|regex:/^[a-zA-Z0-9\s]+$/|between:6,8',
            'city'          => 'required|regex:/^[a-zA-Z\s]+$/',
            'telephone'     => 'regex:/^[0-9\s\-]+$/',
            'mobile'        => 'regex:/^[0-9\s\-]+$/',
        ]);

        if ($validator->passes()) {
            $address = new Address();

            $address->name = $request->input('name');
            $address->street = $request->input('street');
            $address->postcode = $request->input('postcode');
            $address->city = $request->input('city');
            $address->telephone = ($request->input('telephone') ?: '');
            $address->mobile = ($request->input('mobile') ?: '');
            $address->User_id = Auth::user()->company_id;

            $address->save();

            return redirect()
                ->back()
                ->with('status', 'Het adres is toegevoegd');
        } else {
            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * This function handles the removal of an address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeAddress(Request $request)
    {
        if ($request->has('id')) {
            $address = Address::where('id', $request->input('id'))
                ->where('User_id', Auth::user()->company_id)
                ->first();

            if (! empty($address)) {
                $address->delete();

                return redirect('account/addresslist')
                    ->with('status', 'Het adres is verwijderd');
            } else {
                return redirect('account/addresslist')
                    ->withErrors('Het adres bestaat niet of behoort niet bij uw account');
            }
        } else {
            return redirect('account/addresslist')
                ->withErrors('Geen adres id aangegeven');
        }
    }

    /**
     * The user is able to download their discounts file from here in ICC and CSV format.
     *
     * @return \Illuminate\View\View
     */
    public function discountFile()
    {
        return view('account.discountfile');
    }

    /**
     * This will handle the requests for the generation of the discounts file.
     *
     * @param  string  $type
     * @param  string  $method
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function generateFile($type, $method)
    {
        if ($type === 'icc') {
            if ($method === 'download') {
                // Create a filesystem link to the temp file
                $filename = storage_path('icc_data'.Auth::user()->company_id.'.txt');

                // Store the path in flash data so the middleware can delete the file afterwards
                Session::flash('file.download', $filename);

                // File the file with discount data
                File::put($filename, self::discountICC());

                // Return the data as a downloadable file: 'icc_data.txt'
                return Response::download($filename, 'icc_data'.Auth::user()->company_id.'.txt');
            } elseif ($method === 'mail') {
                $filename = storage_path('icc_data'.Auth::user()->company_id.'.txt');

                // Store the path in flash data so the middleware can delete the file afterwards
                Session::flash('file.download', $filename);

                File::put($filename, self::discountICC());

                Mail::send('email.discountfile', [], function ($message) use ($filename) {
                    $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                    $message->to(Auth::user()->email);

                    $message->subject('WTG Webshop ICC kortingen');

                    $message->attach($filename, ['as' => 'icc_data'.Auth::user()->company_id.'.txt']);
                });

                return redirect('account/discountfile')->with('status', 'Het kortingsbestand is verzonden naar '.Auth::user()->email);
            } else {
                return redirect('account/discountfile')
                    ->withErrors('Geen verzendmethode opgegeven');
            }
        } elseif ($type === 'csv') {
            if ($method === 'download') {
                // Create a filesystem link to the temp file
                $filename = storage_path('/icc_data'.Auth::user()->company_id.'.csv');

                // Store the path in flash data so the middleware can delete the file afterwards
                Session::flash('file.download', $filename);

                File::put($filename, self::discountCSV());

                return Response::download($filename, 'icc_data'.Auth::user()->company_id.'.csv');
            } elseif ($method === 'mail') {
                $filename = storage_path('/icc_data'.Auth::user()->company_id.'.csv');

                // Store the path in flash data so the middleware can delete the file afterwards
                Session::flash('file.download', $filename);

                File::put($filename, self::discountCSV());

                Mail::send('email.discountfile', [], function ($message) use ($filename) {
                    $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                    $message->to(Auth::user()->email);

                    $message->subject('WTG Webshop CSV kortingen');

                    $message->attach($filename, [
                        'as' => 'icc_data'.Auth::user()->company_id.'.csv',
                    ]);
                });

                return redirect('account/discountfile')
                    ->with('status', 'Het kortingsbestand is verzonden naar '.Auth::user()->email);
            } else {
                return redirect('account/discountfile')
                    ->withErrors('Geen verzendmethode opgegeven');
            }
        } else {
            return redirect('account/discountfile')
                ->withErrors('Ongeldig bestands type');
        }
    }

    /**
     * The code below is a real mess, I should clean it up sometime
     * but as long as it works, I won't change it too much...
     *
     * TODO: Clean the code below
     */

    /**
     * This function will generate the data for the ICC file.
     *
     * @return string
     */
    private function discountICC()
    {
        /*
         * These variables are static.
         * We only need to set them once
         */
        $GLN = 8714253038995;
        $empty1 = '       ';
        $debiteur = Auth::user()->company_id;
        $empty2 = '               ';
        $date = date('Ymd');
        $version = '1.1  ';
        $name = Auth::user()->company->company;

        /*
         * Used in the rows containing the discounts
         */
        $korting2 = '00000';
        $korting3 = '00000';
        $nettoprijs = '000000000';
        $startdatum = $date;
        $einddatum = 99991231;

        while (strlen($name) <= 70) {
            $name .= ' ';
        }

        $text = '';

        /*
         * Append the "Groepsgebonden" discounts to the ICC file
         */
        $query = DB::table('discounts')
            ->where('User_id', $debiteur)
            ->where('table', 'VA-220')
            ->where('group_desc', '!=', 'Vervallen');

        $groep_korting = $query->get();
        $count = $query->count();

        foreach ($groep_korting as $korting) {
            $groepsnummer = $korting->product;
            while (strlen($groepsnummer) < 20) {
                $groepsnummer .= ' ';
            }
            $artikelnummer = '                    '; //20 empty positions
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->group_desc);
            while (strlen($omschrijving) < 50) {
                $omschrijving .= ' ';
            }
            $korting1 = preg_replace("/\,/", '', $korting->discount);
            $korting1 = ($korting1 < 10 ? '00' : '0').$korting1;
            while (strlen($korting1) < 5) {
                $korting1 .= '0';
            }

            $text .= $groepsnummer.$artikelnummer.$omschrijving.$korting1.$korting2.$korting3.$nettoprijs.$startdatum.$einddatum."\r\n";
        }

        /*
         * Append the "Standaard" discounts to the ICC file
         */
        $query = DB::table('discounts')
            ->where('table', 'VA-221')
            ->where('group_desc', '!=', 'Vervallen')
            ->whereNotIn('product', function ($query) use ($debiteur) {
                $query->select('product')
                    ->from('discounts')
                    ->where('table', 'VA-220')
                    ->where('User_Id', $debiteur);
            });

        $default_korting = $query->get();
        $count = $query->count() + $count;

        foreach ($default_korting as $korting) {
            $groepsnummer = $korting->product;
            while (strlen($groepsnummer) < 20) {
                $groepsnummer .= ' ';
            }
            $artikelnummer = '                    '; //20 empty positions
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->group_desc);
            while (strlen($omschrijving) < 50) {
                $omschrijving .= ' ';
            }
            $korting1 = preg_replace("/\,/", '', $korting->discount);
            $korting1 = ($korting1 < 10 ? '00' : '0').$korting1;
            while (strlen($korting1) < 5) {
                $korting1 .= '0';
            }

            $text .= $groepsnummer.$artikelnummer.$omschrijving.$korting1.$korting2.$korting3.$nettoprijs.$startdatum.$einddatum."\r\n";
        }

        /*
         * Append the "Global Product" discounts to the ICC file
         */
        $query = DB::table('discounts')
            ->where('table', 'VA-261')
            ->whereNotIn('product', function ($query) use ($debiteur) {
                $query->select('product')
                    ->from('discounts')
                    ->where('table', 'VA-260')
                    ->where('User_Id', $debiteur);
            });

        $product_korting = $query->get();
        $count = $query->count() + $count;

        foreach ($product_korting as $korting) {
            $groepsnummer = '                    '; //20 empty positions
            $artikelnummer = $korting->product;
            while (strlen($artikelnummer) < 20) {
                $artikelnummer .= ' ';
            }
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->product_desc);
            while (strlen($omschrijving) < 50) {
                $omschrijving .= ' ';
            }
            $korting1 = preg_replace("/\,/", '', $korting->discount);
            $korting1 = ($korting1 < 10 ? '00' : '0').$korting1;
            while (strlen($korting1) < 5) {
                $korting1 .= '0';
            }

            $text .= $groepsnummer.$artikelnummer.$omschrijving.$korting1.$korting2.$korting3.$nettoprijs.$startdatum.$einddatum."\r\n";
        }

        /*
         * Append the "Productgebonden" discounts to the ICC file
         */
        $query = DB::table('discounts')
            ->where('User_id', $debiteur)
            ->where('table', 'VA-260');

        $product_korting = $query->get();
        $count = $query->count() + $count;

        foreach ($product_korting as $korting) {
            $groepsnummer = '                    '; //20 empty positions
            $artikelnummer = $korting->product;
            while (strlen($artikelnummer) < 20) {
                $artikelnummer .= ' ';
            }
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->product_desc);
            while (strlen($omschrijving) < 50) {
                $omschrijving .= ' ';
            }
            $korting1 = preg_replace("/\,/", '', $korting->discount);
            $korting1 = ($korting1 < 10 ? '00' : '0').$korting1;
            while (strlen($korting1) < 5) {
                $korting1 .= '0';
            }

            $text .= $groepsnummer.$artikelnummer.$omschrijving.$korting1.$korting2.$korting3.$nettoprijs.$startdatum.$einddatum."\r\n";
        }

        /*
         * Prepend the first row last so the count doesnt mess up.
         */
        $count = sprintf("%'06d", $count);
        $text = $GLN.$empty1.$debiteur.$empty2.$date.$count.$version.$name."\r\n".$text;

        return $text;
    }

    /**
     * This function will generate the data for the CSV file.
     *
     * @return string
     */
    private function discountCSV()
    {
        // Static variables
        $firstRow = 'Artikelnr;Omschrijving;Kortingspercentage;ingangsdatum'."\r\n";
        $debiteur = Auth::user()->company_id;
        $date = date('Y-m-d');
        $delimiter = ';';

        $text = $firstRow;

        /*
         * Append the "Groepsgebonden" discounts to the CSV file
         */
        $query = DB::table('discounts')
            ->where('User_id', $debiteur)
            ->where('table', 'VA-220')
            ->where('group_desc', '!=', 'Vervallen');

        $groep_korting = $query->get();

        foreach ($groep_korting as $korting) {
            $groepsnummer = $korting->product;
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->group_desc);
            $korting1 = $korting->discount.'%';

            $text .= $groepsnummer.$delimiter.$omschrijving.$delimiter.$korting1.$delimiter.$date."\r\n";
        }

        /*
         * Append the "Standaard" discounts to the CSV file
         */
        $query = DB::table('discounts')
            ->where('table', 'VA-221')
            ->where('group_desc', '!=', 'Vervallen')
            ->whereNotIn('product', function ($query) use ($debiteur) {
                $query->select('product')
                    ->from('discounts')
                    ->where('table', 'VA-220')
                    ->where('User_Id', $debiteur);
            });

        $default_korting = $query->get();

        foreach ($default_korting as $korting) {
            $groepsnummer = $korting->product;
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->group_desc);
            $korting1 = $korting->discount.'%';

            $text .= $groepsnummer.$delimiter.$omschrijving.$delimiter.$korting1.$delimiter.$date."\r\n";
        }

        /*
         * Append the "Global Product" discounts to the ICC file
         */
        $query = DB::table('discounts')
            ->where('table', 'VA-261')
            ->whereNotIn('product', function ($query) use ($debiteur) {
                $query->select('product')
                    ->from('discounts')
                    ->where('table', 'VA-260')
                    ->where('User_Id', $debiteur);
            });

        $product_korting = $query->get();

        foreach ($product_korting as $korting) {
            $artikelnummer = $korting->product;
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->product_desc);
            $korting1 = $korting->discount.'%';

            $text .= $artikelnummer.$delimiter.$omschrijving.$delimiter.$korting1.$delimiter.$date."\r\n";
        }

        /*
         * Append the "Productgebonden" discounts to the CSV file
         */
        $query = DB::table('discounts')
            ->where('User_id', $debiteur)
            ->where('table', 'VA-260');

        $product_korting = $query->get();

        foreach ($product_korting as $korting) {
            $artikelnummer = $korting->product;
            $omschrijving = preg_replace("/(\r)|(\n)/", '', $korting->product_desc);
            $korting1 = $korting->discount.'%';

            $text .= $artikelnummer.$delimiter.$omschrijving.$delimiter.$korting1.$delimiter.$date."\r\n";
        }

        return $text;
    }
}

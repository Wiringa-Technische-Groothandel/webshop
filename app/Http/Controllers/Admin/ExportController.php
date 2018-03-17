<?php

namespace WTG\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Content;
use App\Product;
use App\Helper;

/**
 * Class ExportController.
 *
 * @package WTG
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class ExportController extends Controller
{

    /**
     * Catalog generation page.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $content = Content::where('name', 'catalog.footer')->first();

        return view('admin.export.index', [
            'currentFooter' => $content->content,
        ]);
    }

    /**
     * Generate the catalog PDF file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function catalog(Request $request)
    {
        if ($request->input('footer') !== '') {
            $footer = Content::where('name', 'catalog.footer')->first();

            $footer->content = $request->input('footer');

            $footer->save();

            unset($footer);
        }

        ini_set('memory_limit', '1G');

        $footer = Content::where('name', 'catalog.footer')->first();

        $productData = \DB::table('products')
            ->orderBy('catalog_group', 'asc')
            ->orderBy('group', 'asc')
            ->orderBy('type', 'asc')
            ->orderBy('number', 'asc')
            ->whereNotIn('action_type', ['Opruiming', 'Actie'])
            ->where('catalog_index', '!=', '')
            ->get();

        \File::put(base_path().'/resources/assets/catalog.html', view('templates.catalogus', ['products' => $productData]));

        exec('wkhtmltopdf --dump-outline "'.base_path().'/resources/assets/tocStyle.xml" -B 15mm --footer-center "'.$footer->content.'" --footer-right [page] --footer-font-size 7 "'.base_path().'/resources/assets/catalog.html" toc --xsl-style-sheet "'.base_path().'/resources/assets/tocStyle.xsl" "'.public_path().'/dl/Wiringa Catalogus.pdf"');

        return redirect()
            ->intended('/dl/Wiringa Catalogus.pdf');
    }

    /**
     * Generate a pricelist for a specific user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function pricelist(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'user_id'   => 'required',
            'separator' => 'required',
            'position'  => 'required',
        ]);

        if (! $validator->fails() && $request->hasFile('file')) {
            $user_id = $request->input('user_id');
            $file = $request->file('file');
            $separator = $request->input('separator');
            $position = $request->input('position');
            $skip = (int) $request->input('skip');
            $count = 0;

            // Create a filesystem link to the temp file
            $filename = storage_path().'/prijslijst_'.$user_id.'.txt';

            // Store the path in flash data so the middleware can delete the file afterwards
            \Session::flash('file.download', $filename);

            $string = "product;netto prijs;prijs per;registratie eenheid\r\n";

            foreach (file($file->getRealPath()) as $input) {
                if ($count >= $skip) {
                    $linedata = str_getcsv($input, $separator);

                    if (isset($linedata[$position - 1])) {
                        $product = Product::select(['number', 'group', 'price', 'refactor', 'price_per', 'special_price', 'registered_per'])
                            ->where('number', $linedata[$position - 1])
                            ->first();

                        if ($product !== null) {
                            if ($product->special_price === '0.00') {
                                $discount = 1 - (Helper::getProductDiscount($user_id, $product->group, $product->number) / 100);
                                $price = number_format(preg_replace("/\,/", '.', $product->price) * $discount, 2, ',', '');
                            } else {
                                $price = number_format(preg_replace("/\,/", '.', $product->special_price), 2, ',', '');
                            }

                            $string .= $product->number.';'.$price.';'.$product->price_per.';'.$product->registered_per."\r\n";
                        }
                    }
                }

                $count++;
            }

            // File the file with discount data
            File::put($filename, $string);

            // Return the data as a downloadable file: 'icc_data.txt'
            return response()
                ->download($filename, 'prijslijst_'.$user_id.'.txt');
        } else {
            return redirect('admin/generate')
                ->withErrors(($request->hasFile('file') === false ? 'Geen bestand geuploaded' : $validator->errors()))
                ->withInput($request->input());
        }
    }

}
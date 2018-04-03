<?php

namespace App\Http\Controllers;

use App\Carousel;
use App\Content;

/**
 * Class HomeController.
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class HomeController extends Controller
{
    /**
     * Homepage.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        return view('home.index', [
            'news'           => Content::where('name', 'home.news')->first(),
            'carouselSlides' => Carousel::orderBy('Order')->get(),
        ]);
    }

    /**
     * About us.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('home.about');
    }

    /**
     * Assortment page.
     *
     * @return \Illuminate\View\View
     */
    public function assortment()
    {
        $manufacturers = json_decode(file_get_contents(base_path('resources/assets/json/manufacturers.json')));

        return view('home.assortment', [
            'manufacturers' => $manufacturers,
        ]);
    }

    /**
     * Contact info.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('home.contact');
    }

    /**
     * Downloads.
     *
     * @return \Illuminate\View\View
     */
    public function downloads()
    {
        return view('home.downloads', [
            'catalogus'      => Content::where('name', 'downloads.catalogus')->first(),
            'flyers'         => Content::where('name', 'downloads.flyers')->first(),
            'artikelbestand' => Content::where('name', 'downloads.artikel')->first(),
        ]);
    }

    /**
     * Licenses.
     *
     * @return \Illuminate\View\View
     */
    public function licenses()
    {
        return view('home.licenses');
    }

    /**
     * Show a pdf inside a view.
     *
     * @param $filename
     *
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filename)
    {
        $path = public_path().'/dl/'.$filename;

        if (! \File::exists($path)) {
            abort(404);
        } else {
            if (\File::mimeType($path) === 'application/pdf') {
                return view('home.showfile', ['file' => $filename]);
            } else {
                return response()->download($path, $filename);
            }
        }
    }

    /**
     * Vacancies page.
     *
     * @return \Illuminate\View\View
     */
    public function vacancies()
    {
        return view('home.vacancies');
    }
}

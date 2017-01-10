<?php

namespace App\Http\Controllers\Admin;

use App\Carousel;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class CarouselController.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CarouselController extends Controller
{
    /**
     * The carousel management page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        return view('admin.carousel.index', [
            'carouselData' => Carousel::orderBy('Order')->get(),
        ]);
    }

    /**
     * Add a carousel slide.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        if ($request->has('title') && $request->has('caption') && $request->hasFile('image')) {
            $image = $request->file('image');
            $title = $request->input('title');
            $caption = $request->input('caption');

            $validator = \Validator::make(
                ['image' => $image],
                ['image' => 'required|image']
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors($validator->errors());
            } else {
                $slide = new Carousel();

                $slide->Image = $image->getClientOriginalName();
                $slide->Title = $title;
                $slide->Caption = $caption;
                $slide->Order = Carousel::count();

                $slide->save();

                $request->file('image')->move(public_path('img/carousel'), $image->getClientOriginalName());

                return redirect()
                    ->back()
                    ->with('status', 'De slide is toegevoegd aan de carousel');
            }
        } else {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors('Een of meer velden zijn niet ingevuld');
        }
    }

    /**
     * Edit the slide order number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        if ($request->has('order') && is_numeric($request->input('order'))) {
            try {
                $slide = Carousel::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                return redirect()
                    ->back()
                    ->withErrors("De slide met id {$id} bestaat niet");
            }

            $slide->Order = $request->input('order');

            $slide->save();

            return redirect()
                ->back()
                ->with('status', 'Het slide nummer is aangepast');
        } else {
            return redirect()
                ->back()
                ->withErrors('Er is een ongeldig slide nummer opgegeven');
        }
    }

    /**
     * Remove a slide from the carousel.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        if (isset($id) && Carousel::where('id', $id)->count() === 1) {
            $slide = Carousel::find($id);

            if (\Storage::disk('local')->exists('/public/img/carousel/'.$slide->Image)) {
                \Storage::disk('local')->delete('/public/img/carousel/'.$slide->Image);
            }

            Carousel::destroy($id);

            return redirect()
                ->back()
                ->with('status', 'De slide is verwijderd uit de carousel');
        } else {
            return redirect()
                ->back()
                ->withErrors("De slide met id {$id} bestaat niet");
        }
    }
}

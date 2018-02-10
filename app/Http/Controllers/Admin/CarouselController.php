<?php

namespace WTG\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CarouselServiceContract;
use WTG\Http\Requests\Admin\Carousel\AddSlideRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use WTG\Http\Requests\Admin\Carousel\DeleteSlideRequest;
use WTG\Http\Requests\Admin\Carousel\UpdateSlideRequest;

/**
 * Carousel controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CarouselController extends Controller
{
    /**
     * @var CarouselServiceContract
     */
    protected $carouselService;

    /**
     * CarouselController constructor.
     *
     * @param  ViewFactory  $view
     * @param  CarouselServiceContract  $carouselService
     */
    public function __construct(ViewFactory $view, CarouselServiceContract $carouselService)
    {
        parent::__construct($view);

        $this->carouselService = $carouselService;
    }

    /**
     * The carousel management page.
     *
     * @return View
     */
    public function getAction(): View
    {
        return $this->view->make('pages.admin.carousel', [
            'slides' => $this->carouselService->getOrderedSlides(),
        ]);
    }

    /**
     * Add a carousel slide.
     *
     * @param  AddSlideRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putAction(AddSlideRequest $request): RedirectResponse
    {
        try {
            $this->carouselService->createSlide(
                $request->input('title'),
                $request->input('caption'),
                $request->file('image')
            );
        } catch (\Exception $e) {
            \Log::notice($e->getMessage(), $e->getTrace());

            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($e->getMessage());
        }

        return redirect()
            ->back()
            ->with('status', __('De slide is toegevoegd aan de carousel.'));
    }

    /**
     * Edit the slide order number.
     *
     * @param  UpdateSlideRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function patchAction(UpdateSlideRequest $request): RedirectResponse
    {
        try {
            $this->carouselService->updateSlide(
                $request->input('slide'),
                $request->input('title'),
                $request->input('caption'),
                $request->input('order')
            );
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->withErrors(__('Geen slide gevonden met id :slide', ['slide' => $request->input('slide')]));
        } catch (\Exception $e) {
            \Log::notice($e->getMessage(), $e->getTrace());

            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($e->getMessage());
        }

        return redirect()
            ->back()
            ->with('status', __('De slide is aangepast.'));
    }

    /**
     * Remove a slide from the carousel.
     *
     * @param  DeleteSlideRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAction(DeleteSlideRequest $request): RedirectResponse
    {
        $id = $request->input('id');

        try {
            $this->carouselService->deleteSlide($id);
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->withErrors(__('Geen slide gevonden met id :slide', ['slide' => $id]));
        } catch (\Exception $e) {
            \Log::notice($e->getMessage(), $e->getTrace());

            return redirect()
                ->back()
                ->withErrors($e->getMessage());
        }

        return redirect()
            ->back()
            ->with('status', __('De slide is verwijderd.'));
    }
}
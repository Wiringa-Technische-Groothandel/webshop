<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Checkout;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use WTG\Contracts\Services\CheckoutServiceContract;
use WTG\Exceptions\Checkout\EmptyCartException;
use WTG\Http\Controllers\Controller;
use WTG\Services\CheckoutService;

/**
 * Finish controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Checkout
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class FinishController extends Controller
{
    /**
     * @var CheckoutService
     */
    protected $checkoutService;

    /**
     * FinishController constructor.
     *
     * @param ViewFactory $view
     * @param CheckoutServiceContract $checkoutService
     */
    public function __construct(ViewFactory $view, CheckoutServiceContract $checkoutService)
    {
        parent::__construct($view);

        $this->checkoutService = $checkoutService;
    }

    /**
     * Order finished page.
     *
     * @return View|RedirectResponse
     */
    public function getAction()
    {
        $order = session('order');

        if (! $order) {
            return back();
        }

        return $this->view->make('pages.checkout.finished', compact('order'));
    }

    /**
     * Finish order action.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postAction(Request $request): RedirectResponse
    {
        try {
            $order = $this->checkoutService->createOrder(
                $request->input('comment')
            );

            session()->flash('order', $order);
        } catch (EmptyCartException $e) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors(__('U kunt geen bestelling afronden met een lege winkelwagen.'));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($e->getMessage());
        }

        return redirect()->route('checkout.finished');
    }
}

<?php

namespace WTG\Http\Controllers\Checkout;

use Illuminate\Http\Request;
use WTG\Services\CheckoutService;
use WTG\Http\Controllers\Controller;
use Illuminate\View\Factory as ViewFactory;
use WTG\Exceptions\Checkout\EmptyCartException;
use WTG\Contracts\Services\CheckoutServiceContract;

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
     * @param  ViewFactory  $view
     * @param  CheckoutServiceContract  $checkoutService
     */
    public function __construct(ViewFactory $view, CheckoutServiceContract $checkoutService)
    {
        parent::__construct($view);

        $this->checkoutService = $checkoutService;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        $order = session('order');

        if (!$order) {
            return back();
        }

        return view('pages.checkout.finished', compact('order'));
    }

    /**
     * Finish order action.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function postAction(Request $request)
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
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($e->getMessage());
        }

        return redirect()->route('checkout.finished');
    }
}
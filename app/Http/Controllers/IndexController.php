<?php

namespace WTG\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * Index controller.
 *
 * @package     WTG\Http
 * @subpackages Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * Handle the request.
     *
     * @param  Request  $request
     * @return View
     */
    public function getAction(Request $request)
    {
        dd($request->user()->getContact());

        return view('pages.index');
    }
}
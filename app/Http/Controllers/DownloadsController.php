<?php

namespace WTG\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * Downloads Controller
 *
 * @package     WTG\Base
 * @subpackage  Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DownloadsController extends Controller
{
    /**
     * Contact page.
     *
     * @param  Request  $request
     * @return View
     */
    public function getAction(Request $request)
    {
        return view('pages.downloads');
    }
}
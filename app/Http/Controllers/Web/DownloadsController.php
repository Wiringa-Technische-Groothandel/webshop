<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\View\View;
use WTG\Http\Controllers\Controller;

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
     * @param Request $request
     * @return View
     */
    public function getAction(Request $request)
    {
        return view('pages.downloads');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
abstract class Controller extends BaseController
{
    /**
     * @return \Illuminate\View\View
     */
    abstract public function view();
}

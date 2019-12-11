<?php

declare(strict_types=1);

namespace WTG\Http\Controllers;

use Illuminate\View\Factory as ViewFactory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Abstract controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @var ViewFactory
     */
    protected ViewFactory $view;

    /**
     * Controller constructor.
     *
     * @param  ViewFactory  $view
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }
}

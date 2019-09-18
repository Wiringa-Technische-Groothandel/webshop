<?php declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Catalog;

use Exception;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory as ViewFactory;

use Throwable;

use WTG\Http\Controllers\Admin\Controller;

/**
 * Product catalog reindex controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ReindexController extends Controller
{
    /**
     * @var ConsoleKernel
     */
    protected $consoleKernel;

    /**
     * ReindexController constructor.
     *
     * @param ViewFactory $view
     * @param ConsoleKernel $consoleKernel
     */
    public function __construct(ViewFactory $view, ConsoleKernel $consoleKernel)
    {
        parent::__construct($view);

        $this->consoleKernel = $consoleKernel;
    }

    /**
     * Trigger indexing.
     *
     * @return RedirectResponse
     */
    public function postAction(): RedirectResponse
    {
        try {
            $this->consoleKernel->call('index:recreate', [
                'index' => config('scout.elasticsearch.index')
            ]);

            $this->consoleKernel->call('scout:import', [
                'model' => \WTG\Models\Product::class
            ]);
        } catch (Exception | Throwable $e) {
            return back()->withErrors($e->getMessage());
        }

        return back()->with('status', __('De producten zijn opnieuw geindexeerd.'));
    }
}
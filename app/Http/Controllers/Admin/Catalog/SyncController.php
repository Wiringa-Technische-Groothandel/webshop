<?php declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Catalog;

use Exception;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;

use Throwable;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Import\ProductImport;

/**
 * Product catalog overview.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SyncController extends Controller
{
    /**
     * @var ProductImport
     */
    protected $productImporter;

    public function __construct(ViewFactory $view, ProductImport $productImporter)
    {
        parent::__construct($view);

        $this->productImporter = $productImporter;
    }

    /**
     * Product overview.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function postAction(Request $request): RedirectResponse
    {
        $sku = $request->input('sku');

        try {
            $this->productImporter->executeSingle($sku);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(__('Geen product gevonden met nummer :sku', [
                'sku' => $sku
            ]));
        } catch (Exception | Throwable $e) {
            return back()->withErrors($e->getMessage());
        }

        return back()->with('status', __('Het product is gesynchroniseerd'));
    }
}
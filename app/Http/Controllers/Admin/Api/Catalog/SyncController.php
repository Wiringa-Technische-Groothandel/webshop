<?php declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Catalog;

use Exception;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Import\ProductImport;

/**
 * Product catalog sync controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SyncController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var ProductImport
     */
    protected ProductImport $productImporter;

    /**
     * SyncController constructor.
     *
     * @param Request $request
     * @param ProductImport $productImporter
     */
    public function __construct(Request $request, ProductImport $productImporter)
    {
        $this->request = $request;
        $this->productImporter = $productImporter;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $sku = (string) $this->request->input('sku');

        try {
            $this->productImporter->executeSingle($sku);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => __('Geen product gevonden met nummer :sku', [ 'sku' => $sku ]),
                'success' => false
            ]);
        } catch (Exception | Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'success' => false
            ]);
        }

        return response()->json([
            'message' => __('Het product is gesynchroniseerd'),
            'success' => true
        ]);
    }
}
<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Catalog;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Import\Importer\SingleProductImporter;
use WTG\Import\ProductImport;
use WTG\Managers\ImportManager;

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
     * @var ImportManager
     */
    protected ImportManager $importManager;

    /**
     * SyncController constructor.
     *
     * @param Request $request
     * @param ImportManager $importManager
     */
    public function __construct(Request $request, ImportManager $importManager)
    {
        $this->request = $request;
        $this->importManager = $importManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $sku = (string)$this->request->input('sku');

        try {
            /** @var SingleProductImporter $importer */
            $importer = app(SingleProductImporter::class);
            $importer->setSku($sku);

            $this->importManager->run($importer);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => __('Geen product gevonden met nummer :sku', ['sku' => $sku]),
                    'success' => false,
                ]
            );
        } catch (Exception | Throwable $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'message' => __('Het product is gesynchroniseerd'),
                'success' => true,
            ]
        );
    }
}

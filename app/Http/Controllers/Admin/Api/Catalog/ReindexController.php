<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Catalog;

use Exception;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Catalog\Model\Product;

/**
 * Product catalog reindex products controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ReindexController extends Controller
{
    /**
     * @var ConsoleKernel
     */
    protected ConsoleKernel $consoleKernel;

    /**
     * SyncController constructor.
     *
     * @param ConsoleKernel $consoleKernel
     */
    public function __construct(ConsoleKernel $consoleKernel)
    {
        $this->consoleKernel = $consoleKernel;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        try {
            $this->consoleKernel->call(
                'index:recreate',
                [
                    'index' => config('scout.elasticsearch.index'),
                ]
            );

            $this->consoleKernel->call(
                'scout:import',
                [
                    'model' => Product::class,
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
                'message' => __('Product indexatie is uitgevoerd.'),
                'success' => true,
            ]
        );
    }
}

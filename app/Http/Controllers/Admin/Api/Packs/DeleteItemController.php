<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Packs;

use Illuminate\Http\Request;
use Illuminate\Log\LogManager;

use Symfony\Component\HttpFoundation\Response;

use Throwable;

use WTG\Catalog\PackManager;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Admin api delete pack item controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DeleteItemController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var PackManager
     */
    protected PackManager $packManager;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * IndexController constructor.
     *
     * @param Request $request
     * @param PackManager $packManager
     * @param LogManager $logManager
     */
    public function __construct(Request $request, PackManager $packManager, LogManager $logManager)
    {
        $this->request = $request;
        $this->packManager = $packManager;
        $this->logManager = $logManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        try {
            $packItem = $this->packManager->findPackItem(
                (int) $this->request->input('id')
            );

            $this->packManager->deletePackItem($packItem);
        } catch (Throwable $throwable) {
            $this->logManager->error($throwable);

            return response()->json([
                'message' => $throwable->getMessage(),
                'success' => false
            ]);
        }

        return response()->json([
            'packs' => $this->packManager->getPacks(),
            'message' => __('Het item is verwijderd.'),
            'success' => true
        ]);
    }
}
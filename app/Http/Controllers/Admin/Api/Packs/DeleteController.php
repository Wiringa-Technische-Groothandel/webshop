<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Packs;

use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\PackManager;

/**
 * Admin api delete pack controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DeleteController extends Controller
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
            $pack = $this->packManager->findPack(
                (int)$this->request->input('id')
            );

            $this->packManager->deletePack($pack);
        } catch (Throwable $throwable) {
            $this->logManager->error($throwable);

            return response()->json(
                [
                    'message' => $throwable->getMessage(),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'packs'   => $this->packManager->getPacks(),
                'message' => __('Het actiepaket is verwijderd.'),
                'success' => true,
            ]
        );
    }
}

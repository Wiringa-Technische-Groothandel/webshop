<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Packs;

use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\PackManager;

/**
 * Admin api packs index controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var PackManager
     */
    protected PackManager $packManager;

    /**
     * IndexController constructor.
     *
     * @param PackManager $packManager
     */
    public function __construct(PackManager $packManager)
    {
        $this->packManager = $packManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            [
                'packs' => $this->packManager->getPacks(),
            ]
        );
    }
}

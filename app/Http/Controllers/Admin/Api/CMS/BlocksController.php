<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\CMS;

use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Block;

/**
 * Admin api blocks controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class BlocksController extends Controller
{
    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            [
                'blocks' => Block::all(),
            ]
        );
    }
}

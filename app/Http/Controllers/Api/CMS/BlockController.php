<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\CMS;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Api\Controller;
use WTG\Models\Block;

/**
 * API CMS block controller.
 *
 * @package     WTG\Http\Controllers\Web\Api\CMS
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class BlockController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * BlocksController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        $blockCode = $this->request->input('code');

        if (! $blockCode) {
            return response()->json(
                [
                    'success' => false,
                    'block'   => null
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $block = Block::where('name', $blockCode)->first();

        return response()->json(
            [
                'success' => (bool)$block,
                'block'   => $block
            ],
            $block ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}

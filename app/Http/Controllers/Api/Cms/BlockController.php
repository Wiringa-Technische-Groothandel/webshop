<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Cms;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Controller;
use WTG\Models\Block;

/**
 * Rest api cms block controller.
 *
 * @author  Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class BlockController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * BlockController constructor.
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
        return response()->json(
            Block::name($this->request->input('name'))->first()
        );
    }
}

<?php

namespace WTG\Http\Controllers\Admin\Content;

use WTG\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use WTG\Contracts\Models\BlockContract;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Block controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Content
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class BlockController extends Controller
{
    /**
     * Get the block details.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function getAction(int $id): JsonResponse
    {
        $block = app()->make(BlockContract::class)->findOrFail($id);

        return response()->json([
            'success' => true,
            'payload' => $block,
            'code' => 200
        ]);
    }

    /**
     * Save the block.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function putAction(Request $request): JsonResponse
    {
        $id = $request->input('block');
        $content = $request->input('data');
        /** @var Block $block */
        $block = app()->make(BlockContract::class)->find($id);

        if (! $block) {
            return response()->json([
                'message' => __('Geen blok gevonden met id :id', ['id' => $id]),
                'success' => false,
                'code' => 404
            ], 404);
        }

        $block->setContent($content);
        $block->save();

        return response()->json([
            'message' => __('Het blok is opgeslagen.'),
            'success' => true,
            'code' => 200
        ]);
    }
}
<?php

namespace WTG\Http\Controllers\Admin\Content;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use WTG\Contracts\Models\DescriptionContract;
use WTG\Contracts\Models\ProductContract;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Description;

/**
 * Description controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Content
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DescriptionController extends Controller
{
    /**
     * Get the description for a product if available.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function postAction(Request $request): JsonResponse
    {
        $sku = $request->input('sku');
        /** @var ProductContract $product */
        $product = app()->make(ProductContract::class)->where('sku', $sku)->first();

        if (! $product) {
            return response()->json([
                'message' => __('Geen product gevonden met nummer :sku', ['sku' => $sku]),
                'success' => false,
                'code' => 404
            ], 404);
        }

        return response()->json([
            'message' => __('Omschrijving voor product :sku', ['sku' => $sku]),
            'success' => true,
            'payload' => $product->getDescription(),
            'code' => 200
        ]);
    }

    public function putAction(Request $request): JsonResponse
    {
        $sku = $request->input('sku');
        $value = $request->input('description');
        /** @var ProductContract $product */
        $product = app()->make(ProductContract::class)->where('sku', $sku)->first();

        if (! $product) {
            return response()->json([
                'message' => __('Geen product gevonden met nummer :sku', ['sku' => $sku]),
                'success' => false,
                'code' => 404
            ], 404);
        }

        if (! $value) {
            if ($product->hasDescription()) {
                /** @var Description $description */
                $description = $product->getDescription();
                $description->delete();
            }
        } else {
            /** @var Description $description */
            $description = $product->getDescription() ?: app()->make(DescriptionContract::class);
            $description->setValue($value);
            $description->setProduct($product);
            $description->save();
        }

        return response()->json([
            'message' => __('De omschrijving is opgeslagen.'),
            'success' => true,
            'code' => 200
        ]);
    }
}
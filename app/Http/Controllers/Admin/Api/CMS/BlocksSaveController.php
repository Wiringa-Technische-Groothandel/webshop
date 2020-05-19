<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\CMS;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Http\Controllers\Controller;
use WTG\Models\Block;

/**
 * Admin api blocks save controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class BlocksSaveController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * BlocksSaveController constructor.
     *
     * @param Request $request
     * @param DatabaseManager $databaseManager
     * @param LogManager $logManager
     */
    public function __construct(Request $request, DatabaseManager $databaseManager, LogManager $logManager)
    {
        $this->request = $request;
        $this->databaseManager = $databaseManager;
        $this->logManager = $logManager;
    }

    public function execute(): Response
    {
        $blockId = $this->request->input('block');
        $content = $this->request->input('content');

        try {
            $this->databaseManager->beginTransaction();

            /** @var Block $block */
            $block = Block::query()->findOrFail($blockId);
            $block->setContent($content);
            $block->saveOrFail();

            $this->databaseManager->commit();
        } catch (Throwable $throwable) {
            $this->databaseManager->rollBack();

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
                'message' => __('Het blok is aangepast.'),
                'success' => true,
            ]
        );
    }
}

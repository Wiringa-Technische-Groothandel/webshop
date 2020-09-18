<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Companies;

use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Http\Controllers\Controller;
use WTG\Models\Company;

/**
 * Admin API delete company controller.
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
     * @var LogManager
     */
    protected LogManager $logManager;

    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * RemoveController constructor.
     *
     * @param Request $request
     * @param LogManager $logManager
     * @param DatabaseManager $databaseManager
     */
    public function __construct(Request $request, LogManager $logManager, DatabaseManager $databaseManager)
    {
        $this->request = $request;
        $this->logManager = $logManager;
        $this->databaseManager = $databaseManager;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function execute(): Response
    {
        try {
            $this->databaseManager->beginTransaction();

            $company = Company::with('customers')
                ->where('id', $this->request->input('id'))
                ->first();

            $company->delete();

            $this->databaseManager->commit();
        } catch (Throwable $e) {
            $this->databaseManager->rollBack();
            $this->logManager->error($e);

            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'message' => __('Het bedrijf is gemarkeerd om definitief verwijderd te worden.'),
                'success' => true,
            ]
        );
    }
}

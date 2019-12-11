<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Companies;

use Exception;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;

use Symfony\Component\HttpFoundation\Response;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Company;

/**
 * Admin API cancel delete company controller.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CancelDeleteController extends Controller
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
                ->onlyTrashed()
                ->where('id', $this->request->input('id'))
                ->first();

            $company->restore();

            $this->databaseManager->commit();
        } catch (\Throwable $e) {
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
                'message' => __('Het bedrijf is teruggezet.'),
                'success' => true,
            ]
        );
    }
}
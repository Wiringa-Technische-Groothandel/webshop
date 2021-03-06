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
use WTG\Contracts\Services\CompanyServiceContract;
use WTG\Exceptions\Company\IncompleteDataException;
use WTG\Http\Controllers\Controller;

/**
 * Admin API update company controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class UpdateController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var DatabaseManager
     */
    protected $dbManager;

    /**
     * @var LogManager
     */
    protected $logManager;

    /**
     * @var CompanyServiceContract
     */
    protected $companyService;

    /**
     * CreateController constructor.
     *
     * @param Request $request
     * @param DatabaseManager $dbManager
     * @param LogManager $logManager
     * @param CompanyServiceContract $companyService
     */
    public function __construct(
        Request $request,
        DatabaseManager $dbManager,
        LogManager $logManager,
        CompanyServiceContract $companyService
    ) {
        $this->request = $request;
        $this->dbManager = $dbManager;
        $this->logManager = $logManager;
        $this->companyService = $companyService;
    }

    /**
     * Create a new company.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function execute(): Response
    {
        $company = null;
        $success = false;

        try {
            $this->dbManager->beginTransaction();

            $this->companyService->updateCompany(
                $this->request->all(
                    [
                        'name',
                        'customer_number',
                        'street',
                        'city',
                        'postcode',
                        'active',
                        'email',
                        'active',
                    ]
                )
            );

            $this->dbManager->commit();

            $message = __('De debiteur is aangepast.');
            $success = true;
        } catch (IncompleteDataException $e) {
            $this->dbManager->rollBack();

            $message = $e->getErrors();
        } catch (Throwable $e) {
            $this->dbManager->rollBack();

            $message = $e->getMessage();

            $this->logManager->error($message);
        }

        return response()->json(
            [
                'message' => $message,
                'success' => $success,
            ]
        );
    }
}

<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Chart;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Controller;
use WTG\Models\Order;

/**
 * Admin API company order chart data controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CompanyOrderController extends Controller
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
     * CreateController constructor.
     *
     * @param Request $request
     * @param DatabaseManager $dbManager
     */
    public function __construct(
        Request $request,
        DatabaseManager $dbManager
    ) {
        $this->request = $request;
        $this->dbManager = $dbManager;
    }

    /**
     * @return JsonResponse
     */
    public function execute(): Response
    {
        $companyId = (int)$this->request->input('id');

        return response()->json(
            [
                'chartData' => $this->getChartData($companyId),
            ]
        );
    }

    /**
     * @param int $companyId
     * @return Collection
     */
    protected function getChartData(int $companyId): Collection
    {
        return Order::select($this->dbManager->raw("COUNT(id) as 'count', YEAR(created_at) as 'year'"))
            ->where('company_id', $companyId)
            ->groupBy($this->dbManager->raw('YEAR(created_at)'))
            ->get();
    }
}

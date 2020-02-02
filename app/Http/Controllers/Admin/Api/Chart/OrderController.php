<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\Chart;

use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Order;

/**
 * Admin API order chart data controller.
 *
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class OrderController extends Controller
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
        $year = (int)$this->request->input('year');

        return response()->json(
            [
                'years' => $this->getYears(),
                'chartData' => $this->getChartData(
                    $year ?: null
                ),
            ]
        );
    }

    /**
     * @return Collection
     */
    protected function getYears(): Collection
    {
        return Order::select($this->dbManager->raw("YEAR(created_at) as 'year'"))
            ->groupBy($this->dbManager->raw('YEAR(created_at)'))
            ->orderBy('year', 'DESC')
            ->pluck('year');
    }

    /**
     * @param int|null $year
     * @return Collection
     */
    protected function getChartData(?int $year = null): Collection
    {
        if ($year === null) {
            $year = Carbon::now()->year;
        }

        return Order::select(
            $this->dbManager->raw("COUNT(id) as 'count', YEAR(created_at) as 'year', MONTH(created_at) as 'month'")
        )
            ->where($this->dbManager->raw('YEAR(created_at)'), $year)
            ->groupBy($this->dbManager->raw('YEAR(created_at), MONTH(created_at)'))
            ->get();
    }
}

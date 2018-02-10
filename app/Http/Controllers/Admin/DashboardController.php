<?php

namespace WTG\Http\Controllers\Admin;

use WTG\Models\Order;
use Illuminate\Http\Request;

/**
 * Dashboard controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DashboardController extends Controller
{
    /**
     * The dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        // SELECT COUNT(id) FROM orders GROUP BY YEAR(created_at), MONTH(created_at);
        $groupedOrders = Order::select(\DB::raw("YEAR(created_at) as 'year'"))
            ->groupBy(\DB::raw('YEAR(created_at)'))
            ->orderBy('year', 'DESC')
            ->get();

        return view('pages.admin.dashboard', [
            'years' => $groupedOrders,
        ]);
    }

    /**
     * Get data for a chart.js chart.
     *
     * @param  string  $type
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chart(Request $request, $type)
    {
        // Get the count, year and month
        if ($type === 'orders') {
            $groupedOrders = Order::select(\DB::raw("COUNT(id) as 'count', YEAR(created_at) as 'year', MONTH(created_at) as 'month'"))
                ->where(\DB::raw('YEAR(created_at)'), $request->input('year'))
                ->groupBy(\DB::raw('YEAR(created_at), MONTH(created_at)'))
                ->get();

            return response()->json([
                'message' => "Chart data for chart '{$type}'",
                'payload' => $groupedOrders,
            ]);
        } elseif ($type === 'browsers') {
            $days = $request->input('days');

            return response()->json([
                'message' => "Chart data for chart '{$type}'",
                'payload' => \Analytics::fetchTopBrowsers(Period::days($days)),
            ]);
        } else {
            return response()->json([
                'message' => 'Unknown chart type',
            ], 400);
        }
    }

}
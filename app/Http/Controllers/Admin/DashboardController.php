<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Content;
use Illuminate\Http\Request;

/**
 * Class DashboardController.
 *
 * @author Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DashboardController extends Controller
{
    /**
     * The dashboard view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        $product_import = Content::where('name', 'admin.product_import')->first();
        $discount_import = Content::where('name', 'admin.discount_import')->first();

        // SELECT COUNT(id) FROM orders GROUP BY YEAR(created_at), MONTH(created_at);
        $groupedOrders = Order::select(\DB::raw("YEAR(created_at) as 'year'"))
            ->groupBy(\DB::raw('YEAR(created_at)'))
            ->orderBy('year', 'DESC')
            ->get();

        return view('admin.dashboard.index', [
            'product_import'    => $product_import,
            'discount_import'   => $discount_import,
            'years'             => $groupedOrders->toArray(),
        ]);
    }

    /**
     * Get the server stats.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $cpuData = $this->cpu();
        $ramData = $this->ram();
        $diskData = $this->disk();

        return response()->json([
            'cpu' => $cpuData,
            'ram' => $ramData,
            'disk' => $diskData,
        ]);
    }

    /**
     * Return the disk usage.
     *
     * @return array
     */
    protected function disk()
    {
        return [
            'total' => disk_total_space('/'),
            'free' => disk_free_space('/'),
        ];
    }

    /**
     * Return the CPU load.
     *
     * @return array
     */
    protected function cpu()
    {
        $uptime = exec('uptime');

        $load = array_slice(explode(' ', str_replace(',', '', $uptime)), -3);
        $max = exec('grep "model name" /proc/cpuinfo | wc -l');

        return [
            'load' => $load[0],
            'max'  => $max,
        ];
    }

    /**
     * Return the RAM usage.
     *
     * @return array
     */
    protected function ram()
    {
        $total = preg_replace("/\D/", '', exec("grep 'MemTotal' /proc/meminfo"));
        $free = preg_replace("/\D/", '', exec("grep 'MemFree' /proc/meminfo"));

        $freePercentage = exec("free -t | grep 'buffers/cache' | awk '{print $4/($3+$4) * 100}'");

        return [
            'total'          => $total,
            'freePercentage' => $freePercentage,
            'free'           => $free,
        ];
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
        } else {
            return response()->json([
                'message' => 'Unknown chart type',
            ], 400);
        }
    }
}

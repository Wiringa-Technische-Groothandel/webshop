<?php

namespace App\Http\Controllers\Admin;

use App\Helper;

/**
 * Class CacheController.
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CacheController extends Controller
{
    /**
     * Cache dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        $opcache = opcache_get_status();

        $opcache_stats = collect($opcache['opcache_statistics']);

        $free_memory = $opcache['memory_usage']['free_memory'];
        $used_memory = $opcache['memory_usage']['used_memory'];
        $wasted_memory = $opcache['memory_usage']['wasted_memory'];

        $total_memory = $free_memory + $used_memory + $wasted_memory;

        /*
         * Calculate opcache memory in MB
         */
        $opcache_memory = collect([
            'total'  => Helper::convertByte($total_memory),
            'free'   => Helper::convertByte($free_memory),
            'used'   => Helper::convertByte($used_memory),
            'wasted' => Helper::convertByte($wasted_memory),
        ]);

        return view('admin.cache.index', [
            'opcache_enabled'       => $opcache['opcache_enabled'],
            'opcache_full'          => $opcache['cache_full'],
            'opcache_stats'         => $opcache_stats,
            'opcache_memory'        => $opcache_memory,
        ]);
    }

    /**
     * Reset the cache.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        if (function_exists('opcache_reset')) {
            if (opcache_reset()) {
                return redirect()
                    ->back()
                    ->with('status', 'De cache is gereset.');
            } else {
                return redirect()
                    ->back()
                    ->withErrors('Er is een fout opgetreden tijdens het resetten van de cache. Mogelijk staat de module \'OpCache\' uit.');
            }
        } else {
            return redirect()
                ->back()
                ->withErrors('Er is een fout opgetreden tijdens het resetten van de cache. De \'OpCache module is niet geinstalleerd.\'');
        }
    }
}

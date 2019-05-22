<?php

namespace WTG\Http\Controllers\Admin\Cache;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use WTG\Http\Controllers\Admin\Controller;

/**
 * Cache index controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Admin\Cache
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * Cache dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function getAction(): View
    {
        if (! extension_loaded('Zend OPcache')) {
            return $this->view->make('pages.admin.cache', [
                'opcache_loaded'  => false,
                'opcache_enabled' => false,
                'opcache_full'    => false,
                'opcache_stats'   => collect(),
                'opcache_memory'  => collect(),
            ]);
        }

        $opcache        = opcache_get_status();
        $opcache_stats  = collect($opcache['opcache_statistics']);

        // Calculate opcache memory in MB
        $opcache_memory = collect([
            'free'   => $opcache['memory_usage']['free_memory'],
            'used'   => $opcache['memory_usage']['used_memory'],
            'wasted' => $opcache['memory_usage']['wasted_memory'],
        ]);

        return $this->view->make('pages.admin.cache', [
            'opcache_loaded'        => true,
            'opcache_enabled'       => $opcache['opcache_enabled'],
            'opcache_full'          => $opcache['cache_full'],
            'opcache_stats'         => $opcache_stats,
            'opcache_memory'        => $opcache_memory,
        ]);
    }

    /**
     * Reset the cache.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAction(): JsonResponse
    {
        if (! extension_loaded('Zend OPcache')) {
            return response()->json(null, 501); // return a 501 "Not implemented" if the module is not loaded
        }

        opcache_reset();

        return response()->json(null, 200); // return 200 OK if the cache was reset
    }
}
<?php

declare(strict_types=1);

namespace WTG\Http\Middleware;

use WTG\Contracts\Models\CustomerContract;

/**
 * Check active middleware.
 *
 * @package     WTG\Http
 * @subpackage  Middleware
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CheckActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (auth('web')->check()) {
            /** @var CustomerContract $customer */
            $customer = $request->user();

            if (! $customer->getActive()) {
                auth('web')->logout();

                return redirect(route('home'))
                    ->withErrors(__('Uw account is gedeactiveerd. Neem contact met ons of uw accountbeheerder op voor meer informatie.'));
            }
        }

        return $next($request);
    }
}
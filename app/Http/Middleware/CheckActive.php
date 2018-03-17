<?php

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
        if (auth()->check()) {
            /** @var CustomerContract $customer */
            $customer = $request->user();

            if (! $customer->getActive()) {
                auth()->logout();

                return redirect(route('home'))
                    ->withErrors(__('Uw account is gedeactiveerd. Voor meer informatie, neem dan contact op met ons of uw account beheerder.'));
            }
        }

        return $next($request);
    }
}
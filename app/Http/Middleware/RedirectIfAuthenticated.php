<?php

namespace WTG\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->check()) {
            if ($guard === 'admin') {
                return redirect(route('admin.dashboard'));
            }

            return redirect(route('home'));
        }

        return $next($request);
    }
}

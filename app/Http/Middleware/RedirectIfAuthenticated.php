<?php

declare(strict_types=1);

namespace WTG\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Redirect if authenticated middleware.
 *
 * @author Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->check()) {
            if ($guard === 'admin') {
                return response()->json(
                    [
                        'user'    => auth()->guard($guard)->user(),
                        'message' => 'Already authenticated',
                        'success' => false,
                    ],
                    400
                );
            }

            return redirect(route('home'));
        }

        return $next($request);
    }
}

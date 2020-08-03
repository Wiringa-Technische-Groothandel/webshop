<?php

declare(strict_types=1);

namespace WTG\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security header middleware.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle($request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (! config('security.headers-enabled')) {
            return $response;
        }

        $response->headers->set('X-XSS-Protection', "1; mode=block");
        $response->headers->set('X-Frame-Options', "SAMEORIGIN");
        $response->headers->set('Referrer-Policy', "same-origin");
        $response->headers->set('Feature-Policy', "autoplay 'none'; camera 'none'");

        return $response;
    }
}

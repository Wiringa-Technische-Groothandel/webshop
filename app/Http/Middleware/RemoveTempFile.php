<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Contracts\Routing\TerminableMiddleware;

class RemoveTempFile implements TerminableMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $file = Session::pull('file.download', null);
        if ($file) {
            unlink($file);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockRegisterAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('register')) {
            abort(404);
        }

        return $next($request);
    }
}

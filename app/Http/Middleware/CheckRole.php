<?php

namespace AttendCheck\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($request->user()->isAdmin() || $request->user()->type == $role) {
            return $next($request);
        }

        abort(401, 'Unauthorized.');
    }
}

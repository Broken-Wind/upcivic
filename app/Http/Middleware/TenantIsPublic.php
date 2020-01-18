<?php

namespace Upcivic\Http\Middleware;

use Closure;

class TenantIsPublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!tenant()->isPublic()) {
            abort(401, 'This action is unauthorized.');
        }
        return $next($request);
    }
}

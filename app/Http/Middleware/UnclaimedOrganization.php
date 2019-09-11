<?php

namespace Upcivic\Http\Middleware;

use Closure;

class UnclaimedOrganization
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

        abort_if($request->route('organization')->isClaimed(), 401, 'This action is unauthorized.');

        return $next($request);
    }
}

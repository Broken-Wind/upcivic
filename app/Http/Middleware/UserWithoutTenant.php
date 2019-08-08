<?php

namespace Upcivic\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserWithoutTenant
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
        if (Auth::user()->organizations->count() > 0) {

            return redirect()->route('tenant:admin.home', \Auth::user()->organizations()->first()['slug']);
        }
        return $next($request);
    }
}

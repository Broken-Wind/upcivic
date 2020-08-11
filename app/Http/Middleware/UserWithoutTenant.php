<?php

namespace App\Http\Middleware;

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
        if (Auth::user()->tenants->count() > 0) {
            return redirect()->route('tenant:admin.home', \Auth::user()->tenants()->first()['slug']);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class AuthToTenant
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

        if (Auth::user()) {

            Auth::user()->authToTenant();

        } else {

            abort(401, 'This action is unauthorized.');

        }

        return $next($request);
    }

}

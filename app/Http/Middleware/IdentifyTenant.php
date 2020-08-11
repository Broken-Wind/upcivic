<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\TenantManager;

class IdentifyTenant
{
    /**
     * @var App\Services\TenantManager
     */
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->tenantManager->loadTenant($request->route('tenant'))) {
            $request->route()->forgetParameter('tenant');

            return $next($request);
        }

        throw new NotFoundHttpException;
    }
}

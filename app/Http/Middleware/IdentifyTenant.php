<?php

namespace Upcivic\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Upcivic\Services\TenantManager;

class IdentifyTenant
{
    /**
     * @var Upcivic\Services\TenantManager
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

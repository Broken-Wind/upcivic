<?php

namespace Upcivic\Providers;

use Illuminate\Support\ServiceProvider;
use Upcivic\Services\TenantManager;
use Upcivic\Tenant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $manager = new TenantManager;

        $this->app->instance(TenantManager::class, $manager);
        $this->app->bind(Tenant::class, function () use ($manager) {
            return $manager->getTenant();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

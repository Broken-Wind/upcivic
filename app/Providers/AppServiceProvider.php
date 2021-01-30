<?php

namespace App\Providers;

use App\Services\TenantManager;
use App\Tenant;
use App\Billing\StripePaymentGateway;
use Illuminate\Support\ServiceProvider;

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

        $this->app->bind(StripePaymentGateway::class, function () {
            return new StripePaymentGateway(config('app.stripe.secret'));
        });

        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
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

<?php
namespace App\Concerns;

use App\Scopes\TenantOwnedScope;
use App\Services\TenantManager;

trait OwnedByTenant {
    public static function bootOwnedByTenant() {

        static::addGlobalScope(new TenantOwnedScope);

    }

    public function tenant() {
        $this->belongsTo(Tenant::class, 'tenant_id');
    }
}

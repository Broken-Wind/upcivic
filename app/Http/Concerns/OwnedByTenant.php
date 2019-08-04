<?php
namespace Upcivic\Concerns;

use Upcivic\Scopes\TenantOwnedScope;
use Upcivic\Services\TenantManager;

trait OwnedByTenant {
    public static function bootOwnedByTenant() {

        static::addGlobalScope(new TenantOwnedScope);

    }

    public function tenant() {
        $this->belongsTo(Tenant::class, 'tenant_id');
    }
}

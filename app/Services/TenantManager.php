<?php
namespace Upcivic\Services;

use Upcivic\Tenant;
use Upcivic\Program;
use Illuminate\Database\Eloquent\Builder;
use Upcivic\Template;

class TenantManager {
    /*
     * @var null|Upcivic\Tenant
     */
     private $tenant;

    public function setTenant(?Tenant $tenant) {
        $this->tenant = $tenant;
        return $this;
    }

    public function getTenant(): ?Tenant {
        return $this->tenant;
    }

    public function forgetTenant()
    {

        $this->tenant = null;

    }


    public function applyGlobalScopes()
    {

        Program::addGlobalScope('TenantAccesibleProgram', function (Builder $builder) {

            return $builder->whereHas('contributors', function ($query) {

                return $query->where('organization_id', tenant()->organization_id);

            });

        });

        Template::addGlobalScope('TenantOwnedTemplate', function ($query) {

            return $query->where('organization_id', tenant()->organization_id);

        });

    }

    public function loadTenant($identifier): bool {

        $tenant = Tenant::query()->where('slug', '=', $identifier)->first();

        if ($tenant) {
            $this->setTenant($tenant);
            $this->applyGlobalScopes();
            return true;
        }

        return false;

    }
 }

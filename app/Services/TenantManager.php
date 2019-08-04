<?php
namespace Upcivic\Services;

use Upcivic\Organization;
use Upcivic\Program;
use Illuminate\Database\Eloquent\Builder;
use Upcivic\Template;

class TenantManager {
    /*
     * @var null|Upcivic\Tenant
     */
     private $tenant;

    public function setTenant(?Organization $tenant) {
        $this->tenant = $tenant;
        return $this;
    }

    public function getTenant(): ?Organization {
        return $this->tenant;
    }


    public function applyGlobalScopes()
    {

        Program::addGlobalScope('tenantAccesibleProgram', function (Builder $builder) {

            return $builder->whereHas('contributors', function ($query) {

                return $query->where('organization_id', tenant()->id);

            });

        });

        Template::addGlobalScope('tenantOwnedProgram', function ($query) {

            return $query->where('organization_id', tenant()->id);

        });

    }

    public function loadTenant($identifier): bool {

        $tenant = Organization::query()->where('slug', '=', $identifier)->first();

        if ($tenant) {
            $this->setTenant($tenant);
            $this->applyGlobalScopes();
            return true;
        }

        return false;

    }
 }

<?php

namespace App\Services;

use App\Assignment;
use App\Program;
use App\Meeting;
use App\Scopes\TenantOwnedScope;
use App\Task;
use App\Template;
use App\Tenant;
use Illuminate\Database\Eloquent\Builder;

class TenantManager
{
    /*
     * @var null|App\Tenant
     */
    private $tenant;

    public function setTenant(?Tenant $tenant)
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getTenant(): ?Tenant
    {
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
            })->whereNotNull('proposed_at')->orWhere('proposing_organization_id', tenant()->organization_id);
        });

        Template::addGlobalScope(new TenantOwnedScope);

        Meeting::addGlobalScope('TenantAccesibleMeeting', function (Builder $builder) {
            return $builder->whereHas('program', function($query) {
                return $query->whereHas('contributors', function ($query) {
                    return $query->where('organization_id', tenant()->organization_id);
                })->whereNotNull('proposed_at')->orWhere('proposing_organization_id', tenant()->organization_id);
            });
        });
        Task::addGlobalScope('TenantAccesibleTask', function (Builder $builder) {
            return $builder->where('organization_id', tenant()->organization_id);
        });
        Task::addGlobalScope('ActiveTask', function (Builder $builder) {
            return $builder->whereNull('archived_at');
        });
        Assignment::addGlobalScope('TenantAccessibleAssignment', function (Builder $builder) {
            return $builder->where('assigned_by_organization_id', tenant()->organization_id)->orWhere('assigned_to_organization_id', tenant()->organization_id);
        });
    }

    public function loadTenant($identifier): bool
    {
        $tenant = Tenant::query()->where('slug', '=', $identifier)->first();

        if ($tenant) {
            $this->setTenant($tenant);
            $this->applyGlobalScopes();

            return true;
        }

        return false;
    }
}

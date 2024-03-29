<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Organization extends GenericAssignableEntity
{
    //
    protected $dispatchesEvents = [
        'created' => \App\Events\OrganizationCreated::class,
    ];

    protected $fillable = [
        'name',
        'enrollment_url',
        'phone',
        'email',
    ];

    public function scopeHasAssignmentsTo($query, $organizationId) {
        return $query->partneredWith($organizationId)->whereHas('outgoingAssignments', function ($query) use ($organizationId) {
            return $query->withoutGlobalScope('OrganizationAssignment')->where('assigned_to_organization_id', $organizationId);
        });
    }

    public function scopePartneredWith($query, $organizationId)
    {
        return $query->whereHas('programs', function ($query) use ($organizationId) {
            return $query->whereHas('contributors', function ($query) use ($organizationId) {
                return $query->where('organization_id', $organizationId);
            })->whereNotNull('proposed_at');
        })->where('id', '!=', $organizationId);
    }

    public function scopeEmailable($query)
    {
        return $query->whereHas('administrators', function ($query) {
            return $query->whereNotNull('email');
        });
    }

    public function scopePubliclyContactable($query)
    {
        return $query->whereNotNull('phone')->orWhereNotNull('email');
    }

    public function getPartnersAttribute()
    {
        return Organization::partneredWith($this->id)->orderBy('name')->get();
    }

    public function isPubliclyContactable()
    {
        return !empty($this->phone) || !empty($this->email);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
    public function hasAreas()
    {
        return $this->areas->isNotEmpty();
    }
    public function outgoingAssignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_by_organization_id');
    }
    public function incomingAssignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_to_organization_id');
    }
    public function outgoingAssignmentsForInstructors()
    {
        return $this->hasMany(Assignment::class, 'assigned_by_organization_id')->forInstructors();
    }
    public function hasOutgoingAssignmentsForInstructors()
    {
        return $this->outgoingAssignmentsForInstructors->isNotEmpty();
    }
    public function incomingAssignmentsForInstructors()
    {
        return $this->hasMany(Assignment::class, 'assigned_to_organization_id')->forInstructors();
    }
    public function hasIncomingAssignmentsForInstructors()
    {
        return $this->incomingAssignmentsForInstructors->isNotEmpty();
    }
    public function incomingAssignedInstructors()
    {
        return $this->belongsToMany(Instructor::class, 'assigned_instructors')->withoutGlobalScope('TenantAccessibleInstructor');
    }
    public function instructorsAssignedTo(Organization $organization)
    {
        return $this->instructors()->whereHas('assignedOrganizations', function ($assignment) use ($organization) {
            return $assignment->where('assigned_instructors.organization_id', $organization->id);
        })->withoutGlobalScope('TenantAccessibleInstructor')->get();
    }
    public function instructorsAssignedBy(Organization $organization)
    {
        return $organization->instructorsAssignedTo($this);
    }

    public function assignInstructorTasksTo($instructorId)
    {
        $this->outgoingAssignmentsForInstructors->each(function ($assignment) use ($instructorId) {
            $assignment->assignToInstructor($instructorId);
        });
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'contributors');
    }
    public function instructors()
    {
        return $this->hasMany(Instructor::class);
    }
    public function hasInstructors()
    {
        return $this->instructors->isNotEmpty();
    }

    public function administrators()
    {
        return $this->belongsToMany(Person::class, 'administrators')->as('administrator')->withPivot('title');
    }

    public function emailableContacts()
    {
        return $this->administrators;
    }

    public function hasAdministratorEmail($email)
    {
        return $this->administrators->pluck('email')->contains($email);
    }

    public function hasEmailableContacts()
    {
        return $this->administrators->count() > 0 ? true : false;
    }

    public function isVacant()
    {
        $contacts = $this->administrators->count();

        if (isset($this->tenant)) {
            $contacts += $this->tenant->users->count();
        }

        return $contacts == 0;
    }

    public function hasTenant()
    {
        return isset($this->tenant);
    }

    public function isClaimed()
    {
        return isset($this->tenant) && $this->tenant->users->count() > 0;
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function getUsersAttribute()
    {
        return $this->tenant->users;
    }

    public function getProposalNextStepsAttribute()
    {
        return $this->tenant->proposal_next_steps;
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function templatesWithoutScope()
    {
        return $this->hasMany(Template::class)->withoutGlobalScopes();
    }

    public function proposals()
    {
        return $this->hasMany(Program::class, 'proposing_organization_id');
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }
}

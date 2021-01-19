<?php

namespace App;

use App\Concerns\IsPerson;
use Illuminate\Database\Eloquent\Model;

class Instructor extends GenericAssignableEntity
{
    use IsPerson;
    protected $with = ['person'];
    //
    public function scopeAssignedToOrganization($query, $organizationId)
    {
        return $query->withoutGlobalScope('TenantAccessibleInstructor')->whereHas('assignedOrganizations', function ($query) use ($organizationId) {
            return $query->where('organization_id', $organizationId);
        });
    }
    public function incomingAssignmentsFrom($organization)
    {
        return $this->incomingAssignments->where('parentAssignment.assigned_by_organization_id', $organization->id);
    }
    public function incomingAssignments()
    {
        return $this->hasMany(InstructorAssignment::class);
    }
    public function meetings()
    {
        return $this->belongsToMany(Meeting::class);
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
    public function assignedOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'assigned_instructors');
    }
    public function assignToOrganization($organizationId)
    {
        $this->assignedOrganizations()->attach($organizationId);
        $organization = Organization::find($organizationId);
        $organization->assignInstructorTasksTo($this->id);
    }

    public function isAssignedTo(Organization $organization)
    {
        return $this->assignedOrganizations()->find($organization->id);
    }
}

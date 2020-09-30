<?php

namespace App;

use App\Concerns\IsPerson;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use IsPerson;
    //
    public function scopeAssignedToOrganization($query, $organizationId)
    {
        return $query->withoutGlobalScope('TenantAccessibleInstructor')->whereHas('assignedOrganizations', function ($query) use ($organizationId) {
            return $query->where('organization_id', $organizationId);
        });
    }
    public function assignments()
    {
        return $this->hasMany(InstructorAssignment::class);
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
}

<?php

namespace App;

use App\Concerns\HasParentAssignment;

class InstructorAssignment extends GenericAssignment implements AssignmentInterface
{
    //
    use HasParentAssignment;
    protected $fillable = [
        'name',
        'description'
    ];
    public function parentAssignment()
    {
        return $this->belongsTo(Assignment::class)->withoutGlobalScope('OrganizationAssignment');
    }
    public function getAssignedByOrganizationIdAttribute()
    {
        return $this->parentAssignment->assigned_by_organization_id;
    }
    public function getAssignedToOrganizationIdAttribute()
    {
        return $this->parentAssignment->assigned_to_organization_id;
    }
}

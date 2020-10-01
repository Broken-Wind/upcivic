<?php

namespace App;

class Assignment extends GenericAssignment
{
    //
    protected $fillable = [
        'name',
        'description'
    ];
    public function scopeOutgoing($query)
    {
        return $query->where('assigned_by_organization_id', tenant()->organization_id);
    }
    public function scopeIncoming($query)
    {
        return $query->where('assigned_to_organization_id', tenant()->organization_id);
    }
    public function scopeForInstructors($query)
    {
        return $query->withoutGlobalScope('OrganizationAssignment')->where('assign_to_entity', Instructor::class);
    }
    public function statusModel()
    {
        return $this->hasOne(AssignmentStatus::class);
    }
    public function getCompletedAtAttribute()
    {
        return $this->statusModel->completed_at;
    }
    public function getApprovedAtAttribute()
    {
        return $this->statusModel->approved_at;
    }
    public function assignedByOrganization()
    {
        return $this->belongsTo(Organization::class, 'assigned_by_organization_id');
    }
    public function assignedToOrganization()
    {
        return $this->belongsTo(Organization::class, 'assigned_to_organization_id');
    }
    public function assignToInstructor($instructorId)
    {
        $instructorAssignment = InstructorAssignment::make();
        $instructorAssignment->parent_assignment_id = $this->id;
        $instructorAssignment->instructor_id = $instructorId;
        $instructorAssignment->save();
    }
}

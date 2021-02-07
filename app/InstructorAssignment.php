<?php

namespace App;

use App\Http\Concerns\HasParentAssignment;

class InstructorAssignment extends GenericAssignment implements AssignmentInterface
{
    //
    use HasParentAssignment;
    protected $fillable = [
        'name',
        'description'
    ];
    public function files(Organization $organization = null)
    {
        $files = $this->ownFiles->merge($this->parentAssignment->files($organization));
        if (!$organization) {
            return $files;
        }
        return $files->where('organization_id', $organization->id);
    }
    public function ownFiles()
    {
        return $this->hasMany(File::class, 'entity_id')->entity($this);
    }
    public function parentAssignment()
    {
        return $this->belongsTo(Assignment::class)->withoutGlobalScope('OrganizationAssignment');
    }
    public function getTaskAttribute()
    {
        return $this->parentAssignment->task;
    }
    public function getAssigneeAttribute()
    {
        return $this->instructor;
    }
    public function instructor()
    {
        return $this->belongsTo(Instructor::class)->withoutGlobalScope('TenantAccessibleInstructor');
    }
    public function getAssignToEntityAttribute()
    {
        return $this->parentAssignment->assign_to_entity;
    }
    public function getAssignedByOrganizationIdAttribute()
    {
        return $this->parentAssignment->assigned_by_organization_id;
    }
    public function getAssignedToOrganizationIdAttribute()
    {
        return $this->parentAssignment->assigned_to_organization_id;
    }
    public function getUploadUrlAttribute()
    {
        return tenant()->route('tenant:admin.instructor_assignments.files.store', [$this->id]);
    }
}

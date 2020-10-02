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
    public function getAssignedByOrganizationIdAttribute()
    {
        return $this->parentAssignment->assigned_by_organization_id;
    }
    public function getAssignedToOrganizationIdAttribute()
    {
        return $this->parentAssignment->assigned_to_organization_id;
    }
}

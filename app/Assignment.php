<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
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
    public function status()
    {
        return $this->hasOne(AssignmentStatus::class);
    }
}

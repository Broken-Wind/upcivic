<?php

namespace App;

class Assignment extends GenericAssignment implements AssignmentInterface
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
    public function files(Organization $organization = null)
    {
        if ($this->task) {
            $files = $this->ownFiles->merge($this->task->files);
        } else {
            $files = collect();
        }
        if (!$organization) {
            return $files;
        }
        return $files->where('organization_id', $organization->id);
    }
    public function task()
    {
        return $this->belongsTo(Task::class)->withoutGlobalScope('TenantAccesibleTask');
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
    public function getCompletedByUserIdAttribute()
    {
        return $this->statusModel->completed_by_user_id;
    }
    public function getApprovedByUserIdAttribute()
    {
        return $this->statusModel->approved_by_user_id;
    }
    public function setCompletedAtAttribute($value)
    {
        $this->statusModel->completed_at = $value;
    }
    public function setApprovedAtAttribute($value)
    {
        $this->statusModel->approved_at = $value;
    }
    public function setCompletedByUserIdAttribute($value)
    {
        $this->statusModel->completed_by_user_id = $value;
    }
    public function setApprovedByUserIdAttribute($value)
    {
        $this->statusModel->approved_by_user_id = $value;
    }
    public function getUploadUrlAttribute()
    {
        return tenant()->route('tenant:admin.assignments.files.store', [$this->id]);
    }
    public function save(array $options = [])
    {
        if ($this->statusModel) {
            $this->statusModel->save();
        }
        parent::save();
    }
    public function assignToInstructor($instructorId)
    {
        $instructorAssignment = InstructorAssignment::make();
        $instructorAssignment->parent_assignment_id = $this->id;
        $instructorAssignment->instructor_id = $instructorId;
        $instructorAssignment->save();
    }

    public function getAcronymsAttribute()
    {
        if(preg_match_all('/\b(\w)/',strtoupper($this->name),$m)) {
            $acronym = implode('',$m[1]);
        }
        return !empty($acronym) ? $acronym : '??';
    }
}

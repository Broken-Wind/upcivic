<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GenericAssignment extends Model
{
    public const STATUSES = [
        'incomplete' => [
            'class_string' => 'alert-danger',
            'status_string' => 'Incomplete'
        ],
        'pending' => [
            'class_string' => 'alert-warning',
            'status_string' => 'Pending Review'
        ],
        'approved' => [
            'class_string' => 'alert-success',
            'status_string' => 'Approved'
        ],
    ];

    public function getStatus()
    {
        switch (true) {
            case ($this->isApproved()):
                return 'approved';
            case ($this->isPending()):
                return 'pending';
            default:
                return 'incomplete';
        }
    }
    public function isApproved()
    {
        return $this->approved_at && $this->completed_at;
    }
    public function isPending()
    {
        return $this->completed_at && !$this->approved_at;
    }
    public function getClassStringAttribute(){
        return self::STATUSES[$this->getStatus()]['class_string'];
    }
    public function getStatusStringAttribute(){
        return self::STATUSES[$this->getStatus()]['status_string'];
    }
    public function canComplete(Organization $organization)
    {
        return $this->assigned_to_organization_id == $organization->id;
    }
    public function canApprove(Organization $organization)
    {
        return $this->assigned_by_organization_id == $organization->id;
    }
    public function complete(User $user) {
        $this->completed_at = Carbon::now();
        $this->completed_by_user_id = $user->id;
        $this->save();
        return $this;
    }
    public function approve(User $user) {
        $this->approved_at = Carbon::now();
        $this->approved_by_user_id = $user->id;
        $this->save();
        return $this;
    }
    public function assignedByOrganization()
    {
        return $this->belongsTo(Organization::class, 'assigned_by_organization_id');
    }
    public function assignedToOrganization()
    {
        return $this->belongsTo(Organization::class, 'assigned_to_organization_id');
    }
    public function isAssignedByOrganization(Organization $organization)
    {
        return $this->assigned_by_organization_id == $organization->id;
    }
    public function isAssignedToOrganization(Organization $organization)
    {
        return $this->assigned_to_organization_id == $organization->id;
    }
    public function ownFiles()
    {
        return $this->hasMany(File::class, 'entity_id')->entity($this);
    }
    public function getAssignerFilesAttribute()
    {
        return $this->files($this->assignedByOrganization);
    }
    public function getAssigneeFilesAttribute()
    {
        return $this->files($this->assignedToOrganization);
    }
}

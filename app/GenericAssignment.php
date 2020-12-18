<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GenericAssignment extends Model
{
    protected $casts = [
        'metadata' => 'array'
    ];
    public const STATUSES = [
        'incomplete' => [
            'class_string' => 'alert-danger',
            'status_string' => 'Incomplete',
            'status_icon_string' => 'fa-times'
        ],
        'pending' => [
            'class_string' => 'alert-warning',
            'status_string' => 'Pending Review',
            'status_icon_string' => 'fa-clock'
        ],
        'approved' => [
            'class_string' => 'alert-success',
            'status_string' => 'Approved',
            'status_icon_string' => 'fa-check'
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
        return $this->approved_at;
    }
    public function isPending()
    {
        return $this->completed_at && !$this->approved_at;
    }
    public function isGenericAssignment()
    {
        return $this->task->type == 'generic_assignment';
    }
    public function isGeneratedDocument()
    {
        return $this->task->type == 'generated_document';
    }
    public function getSignatureFrom(Organization $organization)
    {
        if (isset($this->metadata['assigned_to_organization_signature']['organization_id']) && $this->metadata['assigned_to_organization_signature']['organization_id'] == $organization->id) {
            return $this->metadata['assigned_to_organization_signature'];
        }
        if (isset($this->metadata['assigned_by_organization_signature']['organization_id']) && $this->metadata['assigned_by_organization_signature']['organization_id'] == $organization->id) {
            return $this->metadata['assigned_by_organization_signature'];
        }
        return false;
    }
    public function isSignableBy(Organization $organization, $route)
    {
        if ($this->assignedByOrganization == $organization && $route == 'tenant:admin.assignments.edit') {
            return true;
        }
        if ($this->assignedToOrganization == $organization && $route == 'tenant:assignments.sign') {
            return true;
        }
        return false;
    }
    public function getClassStringAttribute(){
        return self::STATUSES[$this->getStatus()]['class_string'];
    }
    public function getStatusStringAttribute(){
        return self::STATUSES[$this->getStatus()]['status_string'];
    }
    public function getStatusIconStringAttribute(){
        return self::STATUSES[$this->getStatus()]['status_icon_string'];
    }
    public function canComplete(Organization $organization)
    {
        return $this->assigned_to_organization_id == $organization->id
            && !$this->completed_at
            && !$this->approved_at;
    }
    public function canApprove(Organization $organization)
    {
        return $this->assigned_by_organization_id == $organization->id;
    }
    public function complete(User $user = null) {
        $this->completed_at = Carbon::now();
        if (!empty($user)) {
            $this->completed_by_user_id = $user->id;
        }
        $this->save();
        return $this;
    }
    public function approve(User $user) {
        $this->approved_at = Carbon::now();
        $this->approved_by_user_id = $user->id;
        $this->save();
        return $this;
    }
    public function sign($signature)
    {
        // dd($signature);
        $metadata = $this->metadata;
        $metadata = array_merge($metadata, $signature);
        $this->metadata = $metadata;
        $this->save();
    }
    public function isFullySigned()
    {
        return !empty($this->metadata['assigned_by_organization_signature']) && !empty($this->metadata['assigned_to_organization_signature']);
    }
    public function isSignedByOrganization(Organization $organization)
    {
        if (isset($this->metadata['assigned_by_organization_signature']['organization_id']) && $this->metadata['assigned_by_organization_signature']['organization_id'] == $organization->id) {
            return true;
        }
        if (isset($this->metadata['assigned_to_organization_signature']['organization_id']) && $this->metadata['assigned_to_organization_signature']['organization_id'] == $organization->id) {
            return true;
        }
        return false;
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

    public function delete()
    {
        $this->ownFiles->each(function ($file) {
            $file->delete();
        });
        parent::delete();
    }
}

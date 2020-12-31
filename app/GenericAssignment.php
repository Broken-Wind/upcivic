<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GenericAssignment extends Model
{
    public const STATUSES = [
        'incomplete' => [
            'class_string' => 'alert-danger',
            'status_string' => 'Incomplete',
            'status_icon_string' => 'fa-folder-minus'
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
        return $this->type == 'generic_assignment';
    }
    public function isSignableDocument()
    {
        return $this->type == 'signable_document';
    }
    public function getSignatureFrom(Organization $organization)
    {
        return $this->signableDocument->signatures->firstWhere('organization_id', $organization->id) ?? false;
    }
    public function isSignableBy(Organization $organization, $route)
    {
        if ($this->assignedToOrganization == $organization && $route == 'tenant:assignments.public.edit') {
            return true;
        }

        if (tenant()->organization == $organization && $route == 'tenant:admin.assignments.edit') {
            if ($this->assignedByOrganization == $organization || $this->assignedToOrganization == $organization) {
                return true;
            }
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
            && !$this->approved_at
            && $this->isGenericAssignment();
    }
    public function canUpload(Organization $organization)
    {
        if ($this->assigned_by_organization_id == $organization->id) {
            return true;
        };
        if ($this->assigned_to_organization_id == $organization->id && $this->type == 'generic_assignment') {
            return true;
        }
        return false;
    }
    public function canApprove(Organization $organization)
    {
        return $this->assigned_by_organization_id == $organization->id;
    }
    public function canDelete(Organization $organization)
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

    public function getTypeAttribute()
    {
        return $this->task->type;
    }

    public function signableDocument()
    {
        return $this->hasOne(SignableDocumentAssignment::class);
    }
    public function isFullySigned()
    {
        return $this->isSignedByOrganization($this->assignedByOrganization)
                && $this->isSignedByOrganization($this->assignedToOrganization);
    }
    public function isSignedByOrganization(Organization $organization)
    {
        return $this->type == 'signable_document'
                && $this->signableDocument->signatures->where('organization_id', $organization->id)->isNotEmpty();
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

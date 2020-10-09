<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class GenericAssignableEntity extends Model
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

    public function getSelfStatusFor(Organization $organization)
    {
        switch (true) {
            case ($this->isApprovedBy($organization)):
                return 'approved';
            case ($this->isPendingBy($organization)):
                return 'pending';
            default:
                return 'incomplete';
        }
    }
    public function assignmentsBy(Organization $organization)
    {
        return $this->incomingAssignments->where('assigned_by_organization_id', $organization->id);
    }
    public function hasAssignmentsBy(Organization $organization)
    {
        return $this->assignmentsBy($organization)->count() > 0;
    }
    public function isApprovedBy(Organization $organization)
    {
        if (!$this->hasAssignmentsBy($organization)) {
            return true;
        }
        return  $this->hasAssignmentsBy($organization) &&
                $this->assignmentsBy($organization)->filter(function ($assignment) {
            return !$assignment->isApproved();
        })->count() == 0;
    }
    public function isPendingBy(Organization $organization)
    {
        return  $this->hasAssignmentsBy($organization) &&
                $this->assignmentsBy($organization)->filter(function ($assignment) {
            return $assignment->isPending();
        })->count() != 0;
    }
    public function getSelfClassStringFor(Organization $organization){
        return self::STATUSES[$this->getSelfStatusFor($organization)]['class_string'];
    }
    public function getSelfStatusStringFor(Organization $organization){
        return self::STATUSES[$this->getSelfStatusFor($organization)]['status_string'];
    }
}

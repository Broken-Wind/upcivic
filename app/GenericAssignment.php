<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class GenericAssignment extends Model
{
    public const STATUSES = [
        'incomplete' => [
            'class_string' => 'alert-danger',
            'status_string' => 'Incomplete'
        ],
        'pending_review' => [
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
        return $this->completed_at;
    }
    public function getClassStringAttribute(){
        return self::STATUSES[$this->getStatus()]['class_string'];
    }
    public function getStatusStringAttribute(){
        return self::STATUSES[$this->getStatus()]['status_string'];
    }
}

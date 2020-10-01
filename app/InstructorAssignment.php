<?php

namespace App;

use App\Concerns\HasParentAssignment;

class InstructorAssignment extends GenericAssignment
{
    //
    use HasParentAssignment;
    protected $fillable = [
        'name',
        'description'
    ];
    public function parentAssignment()
    {
        return $this->belongsTo(Assignment::class)->withoutGlobalScope('OrganizationAssignment');
    }
}

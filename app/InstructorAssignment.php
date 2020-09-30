<?php

namespace App;

use App\Concerns\HasParentAssignment;
use Illuminate\Database\Eloquent\Model;

class InstructorAssignment extends Model
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

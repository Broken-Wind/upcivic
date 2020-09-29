<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorAssignment extends Model
{
    //
    public function parentAssignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}

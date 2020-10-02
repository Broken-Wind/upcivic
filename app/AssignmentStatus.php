<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentStatus extends Model
{
    //
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}

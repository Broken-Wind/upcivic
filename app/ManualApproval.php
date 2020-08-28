<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManualApproval extends Model
{
    //
    protected $fillable = [
        'person_id',
        'contributor_id'
    ];
}

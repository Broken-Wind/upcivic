<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //

    protected $fillable = [
        'name',
        'description',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_to_organization_id');
    }
}

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
        return $this->hasMany(Assignment::class);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'entity_id')->entity($this);
    }
}

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
        return $this->hasMany(File::class, 'uploaded_to_entity_id')->entity(self::class);
    }
}

<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;
use Upcivic\Organization;

class Administrator extends Model
{
    //
    protected $fillable = [

        'title',

    ];

    public function getNameAttribute()
    {

        return $this->person->name;

    }
    public function getFirstNameAttribute()
    {

        return $this->person->first_name;

    }
    public function getLastNameAttribute()
    {

        return $this->person->last_name;

    }

    public function getEmailAttribute()
    {

        return $this->person->email;

    }

    public function person()
    {

        return $this->belongsTo(Person::class);

    }

    public function organization()
    {

        return $this->belongsTo(Organization::class);

    }
}

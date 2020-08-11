<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    //
    protected $fillable = [

        'first_name',

        'last_name',

        'email',

    ];

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}

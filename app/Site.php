<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [

        'name',

        'address',

        'phone',
    ];

    //
    public function meetings()
    {

        return $this->belongsToMany(Meeting::class);

    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    //
    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'needs'
    ];
    public function contacts()
    {
        return $this->belongsToMany(Person::class, 'contacts');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

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
    protected $dates = [
        'birthday'
    ];
    public function contacts()
    {
        return $this->belongsToMany(Person::class, 'contacts')->withPivot('type');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getFormattedBirthdayAttribute()
    {
        return $this->birthday->format('n/j/Y');
    }
    public function primaryContact()
    {
        return $this->contacts()->wherePivot('type', 'primary')->first();
    }
}

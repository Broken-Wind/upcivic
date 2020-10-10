<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    //
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone'
    ];
    public $attributes;
    public function __construct()
    {
        $this->attributes = $this->fillable;
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getInitialsAttribute()
    {
        $first = '';
        $last = '';
        if ($this->first_name) {
            $first = substr($this->first_name[0], 0, 1);
        }
        if ($this->last_name) {
            $last = substr($this->last_name[0], 0, 1);
        }
        $initials = strtoupper($first . $last);
        return !empty($initials) ? $initials : '??';
    }

    public function instructors()
    {
        return $this->hasMany(Instructor::class);
    }
}

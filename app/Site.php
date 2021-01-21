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

    public function getLinkedPinHtml()
    {
        return "<a href=\"https://www.google.com/maps/search/?api=1&query={$this['address']}\" target=\"_blank\"><i class=\"fas fa-fw fa-map-marker-alt ml-2\"></i></a>";
    }

    //
    public function meetings()
    {
        return $this->belongsToMany(Meeting::class);
    }

    public function getAreaAttribute()
    {
        return $this->areas->first() ?? Area::defaultArea();
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }

    public function isVirtual()
    {
        return $this->name == '[VIRTUAL]';
    }
}

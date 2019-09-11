<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    //
    protected $fillable = [
        'slug',
    ];

    public function users()
    {

        return $this->belongsToMany(User::class);

    }

    public function getNameAttribute()
    {

        return $this->organization->name;

    }

    public function organization()
    {

        return $this->belongsTo(Organization::class);

    }

    public function route($name, $parameters = [], $absolute = true) {
        return app('url')->route($name, array_merge([$this->slug], $parameters), $absolute);
    }
}

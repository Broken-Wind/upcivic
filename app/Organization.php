<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    protected $fillable = [
        'name', 'slug',
    ];

    public function users()
    {

        return $this->belongsToMany(User::class);

    }

    public function templates()
    {

        return $this->hasMany(Template::class);

    }

    public function route($name, $parameters = [], $absolute = true) {
        return app('url')->route($name, array_merge([$this->slug], $parameters), $absolute);
    }

}

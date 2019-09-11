<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Organization extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function administrators()
    {

        return $this->hasMany(Administrator::class);

    }

    public function isClaimed()
    {

        return isset($this->tenant) && $this->tenant->users->count() > 0;

    }

    public function tenant()
    {

        return $this->hasOne(Tenant::class);
    }

    public function templates()
    {

        return $this->hasMany(Template::class);

    }

    public function templatesWithoutScope()
    {

        return $this->hasMany(Template::class)->withoutGlobalScopes();

    }

}

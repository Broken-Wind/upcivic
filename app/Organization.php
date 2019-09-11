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

    public function scopePublished($query)
    {

        return $query->whereNotNull('published_at');

    }

    public function isPublished()
    {

        return $this['published_at'] != null;

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

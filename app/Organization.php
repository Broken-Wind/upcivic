<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Organization extends Model
{
    //
    protected $fillable = [
        'name', 'slug', 'published_at'
    ];

    public function scopePublished($query)
    {

        return $query->whereNotNull('published_at');

    }

    public function isPublished()
    {

        return $this['published_at'] != null;

    }

    public function users()
    {

        return $this->belongsToMany(User::class);

    }

    public function templates()
    {

        return $this->hasMany(Template::class);

    }

    public function templatesWithoutScope()
    {

        return $this->hasMany(Template::class)->withoutGlobalScopes();

    }

    public function route($name, $parameters = [], $absolute = true) {
        return app('url')->route($name, array_merge([$this->slug], $parameters), $absolute);
    }

}

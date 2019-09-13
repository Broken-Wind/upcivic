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

    public function scopeEmailable($query)
    {

        return $query->whereHas('administrators', function ($query) {

            return $query->whereHas('person', function ($query) {

                return $query->whereNotNull('email');

            });

        });

    }


    public function administrators()
    {

        return $this->hasMany(Administrator::class);

    }

    public function emailableContacts()
    {

        return $this->administrators;

    }

    public function hasAdministratorEmail($email)
    {

        return $this->administrators->pluck('email')->contains($email);

    }

    public function hasEmailableContacts()
    {

        return $this->administrators->count() > 0 ? true : false;

    }

    public function vacant()
    {

        $contacts = $this->administrators->count();

        if (isset($this->tenant)) {

            $contacts += $this->tenant->users->count();

        }

        return $contacts == 0;

    }

    public function hasTenant()
    {

        return isset($this->tenant);

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

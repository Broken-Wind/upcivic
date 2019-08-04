<?php

namespace Upcivic;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function authToTenant()
    {

        return $this->organizations->contains(tenant()) ?: abort(401, 'This action is unauthorized.');

    }

    public function organizations()
    {

        return $this->belongsToMany(Organization::class);

    }

    public function join(Organization $organization)
    {

        $this->organizations()->attach($organization);

        return $this;

    }

    public function memberOf(Organization $organization)
    {

        return $this->organizations->contains($organization);

    }

    public function hasOrganization()
    {

        return $this->organizations()->count() > 0;

    }
}

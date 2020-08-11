<?php

namespace Upcivic;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        return $this->tenants()->get()->contains(tenant()) ?: abort(401, 'This action is unauthorized.');
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function joinTenant(Tenant $tenant)
    {
        $this->tenants()->attach($tenant);

        if (! $tenant->organization->administrators->pluck('email')->contains($this->email)) {
            $exploded = explode(' ', $this->name);

            $firstName = $exploded[0];

            $lastName = array_last($exploded);

            $person = Person::create([

                'first_name' => $firstName,

                'last_name' => $lastName,

                'email' => $this->email,

            ]);

            $tenant->organization->administrators()->save($person);
        }

        return $this;
    }

    public function memberOfTenant(Tenant $tenant)
    {
        return $this->tenants->contains($tenant);
    }

    public function hasTenant()
    {
        return $this->tenants()->count() > 0;
    }

    public function hasRecommendedOrganizations()
    {
        return $this->recommendedOrganizations()->isNotEmpty();
    }

    public function recommendedOrganizations()
    {
        return Organization::whereHas('administrators', function ($administrator) {
            return $administrator->where('email', $this['email']);
        })->get();
    }
}

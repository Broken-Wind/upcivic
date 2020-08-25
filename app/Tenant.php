<?php

namespace App;

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

    public function getAggregatedAdministratorsAttribute()
    {
        $aggregatedAdministrators = $this->users->map(function ($user) {
            return collect([
                'name' => $user['name'],
                'email' => $user['email'],
                'is_user' => true,
                'is_administrator' => false,
            ]);
        });
        $this->organization->administrators->each(function ($administrator) use ($aggregatedAdministrators) {
            if ($aggregatedAdministrators->contains('email', $administrator['email'])) {
                $aggregatedAdministrators->where('email', $administrator['email'])->first()['is_administrator'] = true;

                return;
            }
            $aggregatedAdministrators->push(collect([
                'name' => $administrator['name'],
                'email' => $administrator['email'],
                'is_user' => false,
                'is_administrator' => true,
            ]));
        });

        return $aggregatedAdministrators;
    }

    public function getNameAttribute()
    {
        return $this->organization->name;
    }

    public function isPublic()
    {
        return $this['id'] == 2 && $this['slug'] == 'techsplosion';
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        return app('url')->route($name, array_merge([$this->slug], $parameters), $absolute);
    }
}

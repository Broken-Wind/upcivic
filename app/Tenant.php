<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tenant extends Model
{
    //
    protected $fillable = [
        'slug',
        'proposal_next_steps'
    ];
    protected $casts = [
        'next_payment_due_at' => 'datetime'
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
        return true;
    }

    public function isSubscribed()
    {
        $currentUser = Auth::user();
        $subscriptionName = config('app.subscription_name');
        $noOfUsers = $this->users->count();

        if ($currentUser->subscribed($subscriptionName)) {
            return true;
        } 
        
        foreach($this->users->all() as $user) {
            if ($user->subscribed($subscriptionName)) {

                $noOfSeats = $user->subscription($subscriptionName)->quantity;
                if ($noOfUsers > $noOfSeats) {
                    return false;
                }
                return true;
            }
        }
        return false;

    }

    public function getPlanTypeAttribute()
    {
        return $this->isSubscribed() ? 'basic_host_v1' : 'free_v1';
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

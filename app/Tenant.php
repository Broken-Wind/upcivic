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

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function acceptsRegistrations()
    {
        return $this->isSubscribed() && $this->hasStripeCredentials();
    }

    public function hasStripeCredentials()
    {
        return !empty($this->stripe_account_id) && !empty($this->stripe_access_token);
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

    public function getPhoneAttribute()
    {
        return $this->organization->phone;
    }

    public function getEmailAttribute()
    {
        return $this->organization->email;
    }

    public function isPublic()
    {
        return true;
    }

    public function isSubscribed()
    {

        $currentUser = Auth::user();
        $subscriptionName = config('services.stripe.subscription_name');

        if (empty($currentUser)){
            return false;
        }
        if ($currentUser->onTrial()) {
            return true;
        }

        if ($currentUser->subscribed($subscriptionName)) {
            return true;
        }

        if ($this->hasAvailableProSeats()) {
            return true;
        }

        return false;

    }

    public function hasAvailableProSeats() {

        $numberOfUsers = $this->users->count();
        $subscriptionName = config('services.stripe.subscription_name');

        foreach($this->users->all() as $user) {
            if ($user->subscribed($subscriptionName)) {

                $numberOfSeats = $user->subscription($subscriptionName)->quantity;
                if ($numberOfUsers > $numberOfSeats) {
                    return false;
                }
                return true;
            }
        }

    }

    public function hasStripeCustomer() {

        $subscriptionName = config('services.stripe.subscription_name');

        foreach($this->users->all() as $user) {
            if ($user->subscribed($subscriptionName)) {
                return true;
            }
            return false;
        }

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

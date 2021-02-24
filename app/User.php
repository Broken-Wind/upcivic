<?php

namespace App;

use Carbon\Carbon;
use Laravel\Cashier\Billable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use App\Exceptions\NoMoreSeatsException;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password',
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
        'trial_ends_at' => 'datetime',
    ];

    public function authToTenant()
    {
        return $this->tenants()->get()->contains(tenant()) ?: abort(401, 'This action is unauthorized.');
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class);
    }
    public function getOrganizationsAttribute()
    {
        return $this->tenants->map(function ($tenant) {
            return $tenant->organization;
        });
    }

    public function joinTenant(Tenant $tenant)
    {
        $subscriptionName = config('services.stripe.subscription_name');
        $numberOfUsers = $tenant->users->count() + 1;

        foreach($tenant->users->all() as $user) {
            if ($user->subscribed($subscriptionName)) {
                $numberOfSeats = $user->subscription($subscriptionName)->quantity;
                if ($numberOfUsers > $numberOfSeats) {
                    throw new NoMoreSeatsException();
                }
            }
        }

        $this->tenants()->attach($tenant);

        if (! $tenant->organization->administrators->pluck('email')->contains($this->email)) {
            $exploded = explode(' ', $this->name);

            $firstName = $exploded[0];

            $lastName = Arr::last($exploded);

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

    public function approveProgram(Program $program) {
       $contributor = $program->contributors()->where('organization_id', tenant()->organization_id)->firstOrFail();
       $this->approveProgramForContributor($program, $contributor);
    }

    public function approveProgramForContributor(Program $program, Contributor $contributor){

        $contributor->approved_by_user_id = $this->id;
        $contributor->approved_at = Carbon::now();

        $contributor->save();
    }

    public function canGenerateDemoData()
    {
        return App::environment() == 'local' || App::environment() == 'demo';
    }

    public function isPaymentCardHolder() {
        return ($this->card_last_four != null);
    }

}

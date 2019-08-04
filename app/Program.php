<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;
use Upcivic\Concerns\HasDatetimeRange;

class Program extends Model
{
    use HasDatetimeRange;
    //
    protected $fillable = [

        'name',

        'internal_name',

        'description',

        'invoice_amount',

        'invoice_type',

        'ages_type',

        'min_age',

        'max_age',

    ];

    public function delete()
    {

        $this->meetings()->delete();

        parent::delete();

    }

    public function getSiteAttribute () {

        $sites = collect([]);

        foreach ($this->meetings as $meeting) {

            $sites->push($meeting->site);

        }

        return $sites->where('name', $sites->mode('name')[0])->first();

    }

    public function getLocationAttribute () {

        $location = collect([]);

        foreach ($this->meetings as $meeting) {

            $location->push($meeting->location);

        }

        return $location->where('name', $location->mode('name')[0])->first();

    }

    public function meetings()
    {

        return $this->hasMany(Meeting::class);

    }

    public function contributors()
    {

        return $this->hasMany(Contributor::class);

    }

    public function getInternalNameAttribute()
    {

        return $this->contributors()->where('organization_id', tenant()['id'])->first()['internal_name'] ?? $this['name'];

    }

    public function setInternalNameAttribute($internalName)
    {

        return $this->contributors()->where('organization_id', tenant()['id'])->first()->update(['internal_name' => $internalName]);

    }

    public function getStartDatetimeAttribute()
    {

        return $this->firstMeeting()['start_datetime'];

    }

    public function getEndDatetimeAttribute()
    {

        return $this->lastMeeting()['end_datetime'];

    }

    public function firstMeeting()
    {

        return $this->meetings()->orderBy('start_datetime')->first();

    }

    public function lastMeeting()
    {

        return $this->meetings()->orderByDesc('start_datetime')->first();

    }

}

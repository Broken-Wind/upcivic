<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

class ProgramFilters extends QueryFilters
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function setUp()
    {
        return isset($this->request['past']) && $this->request['past'] == true ? $this->builder : $this->builder->excludePast();
    }

    public function organization($term)
    {
        return $this->builder->whereHas('contributors', function ($query) use ($term) {
            return $query->where('organization_id', $term);
        });
    }

    public function site($term)
    {
        return $this->builder->whereHas('meetings', function ($query) use ($term) {
            // I considered requiring & defaulting meeting->site_id to 0, which would negate the need for this conditional,
            // But that would break the foreign key constraint. Better to leave it nullable and do this manually.
            if ($term == 'tbd') {
                return $query->whereNull('site_id');
            }
            return $query->where('site_id', $term);
        });
    }

    public function counties($term)
    {
        return $this->builder->whereHas('meetings', function ($query) use ($term) {
            return $query->whereHas('site', function ($query) use ($term) {
                return $query->whereIn('county_id', explode(',', $term));
            });
        });
    }

    public function from_date($term)
    {
        return $this->builder->withoutGlobalScope('ExcludePastPrograms')->whereHas('meetings', function ($query) use ($term) {
            return $query->where('end_datetime', '>', $term);
        });
    }

    public function to_date($term)
    {
        return $this->builder->whereHas('meetings', function ($query) use ($term) {
            return $query->where('start_datetime', '<', Carbon::parse($term)->addDay());
        });
    }

    // public function age($term) {
    //     $year = Carbon::now()->subYear($age)->format('Y');
    //     return $this->builder->where('dob', '>=', "$year-01-01")->where('dob', '<=', "$year-12-31");
    // }

    // public function sort_age($type = null) {
    //     return $this->builder->orderBy('dob', (!$type || $type == 'asc') ? 'desc' : 'asc');
    // }
}

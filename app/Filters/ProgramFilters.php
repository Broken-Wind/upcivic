<?php
namespace Upcivic\Filters;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ProgramFilters extends QueryFilters
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function organization($term) {

        return $this->builder->whereHas('contributors', function ($query) use ($term) {
            return $query->where('organization_id', $term);
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

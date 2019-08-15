<?php
namespace Upcivic\Concerns;

use App\Filters\QueryFilters;

trait Filterable
{
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}

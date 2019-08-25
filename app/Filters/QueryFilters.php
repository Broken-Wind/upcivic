<?php
namespace Upcivic\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
class QueryFilters
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        $this->setUp();

        foreach ($this->filters() as $name => $value) {
            if ( ! method_exists($this, $name)) {
                continue;
            }
            if (strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }
        return $this->builder;
    }

    public function filters()
    {
        return collect($this->request->all())->filter(function($value) {
            return null !== $value;
        })->toArray();
    }
}
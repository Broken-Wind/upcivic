<?php

namespace App\Http\Concerns;
trait HasParentAssignment
{
    public function getNameAttribute()
    {
        return $this->parentAssignment->name;
    }
    public function getDescriptionAttribute()
    {
        return $this->parentAssignment->description;
    }
}

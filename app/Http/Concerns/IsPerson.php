<?php

namespace App\Concerns;
trait IsPerson
{
    public function getNameAttribute()
    {
        return $this->person->name;
    }
    public function getInitialsAttribute()
    {
        return $this->person->initials;
    }
}

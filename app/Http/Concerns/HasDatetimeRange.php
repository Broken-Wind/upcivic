<?php
namespace App\Concerns;

use Carbon\Carbon;

trait HasDatetimeRange {

    public function getStartTimeAttribute()
    {

        return Carbon::parse($this['start_datetime'])->format('g:ia');

    }

    public function getEndTimeAttribute()
    {

        return Carbon::parse($this['end_datetime'])->format('g:ia');

    }

    public function getStartDateAttribute()
    {

        return Carbon::parse($this['start_datetime'])->format('n/j/y');

    }

    public function getEndDateAttribute()
    {

        return Carbon::parse($this['end_datetime'])->format('n/j/y');

    }

    public function getShortStartDateAttribute()
    {

        return Carbon::parse($this['start_datetime'])->format('n/j');

    }

    public function getShortEndDateAttribute()
    {

        return Carbon::parse($this['end_datetime'])->format('n/j');

    }


}

<?php

namespace Upcivic;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Upcivic\Concerns\HasDatetimeRange;

class Meeting extends Model
{
    //
    use HasDatetimeRange;
    protected $dates = ['start_datetime', 'end_datetime'];
    protected $fillable = [
        'start_datetime',
        'end_datetime',
    ];

    public function getLinkedPinHtml()
    {
        return $this->site->getLinkedPinHtml();
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class)->withDefault([
            'name' => 'Site TBD',
            'address' => 'TBD',
            'phone' => 'TBD',
        ]);
    }

    public function getLocationAttribute()
    {
        return $this->location ?? new Location([
            'name' => 'Location TBD',
        ]);
    }
}

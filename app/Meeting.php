<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Concerns\HasDatetimeRange;

class Meeting extends Model
{
    //
    use HasDatetimeRange;

    protected $fillable = [

        'start_datetime',

        'end_datetime',

        'note',

    ];

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

            'name' => "Site TBD",

            'address' => 'TBD',

            'phone' => 'TBD',

        ]);

    }

    public function getLocationAttribute()
    {

        return $this->location ?? new Location([

            'name' => "Location TBD"

        ]);

    }
}

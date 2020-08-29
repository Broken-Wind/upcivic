<?php

namespace App;

use App\Concerns\HasDatetimeRange;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Location::class)->withDefault([
            'name' => 'Location TBD',
        ]);
    }

    public function site()
    {
        return $this->belongsTo(Site::class)->withDefault([
            'name' => 'Site TBD',
            'address' => 'TBD',
            'phone' => 'TBD',
        ]);
    }
}

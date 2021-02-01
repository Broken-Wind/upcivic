<?php

namespace App;

use App\Http\Concerns\HasDatetimeRange;
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

    public function scopeExcludePast($query)
    {
        return $query->where('end_datetime', '>', Carbon::now()->subDays(5));
    }

    public function getLinkedPinHtml()
    {
        return $this->site->getLinkedPinHtml();
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class);
    }

    public function hasInstructors()
    {
        return $this->instructors->isNotEmpty();
    }

    public function getInstructorListAttribute()
    {
        return $this->instructors->pluck('first_name')->implode(', ');
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

    public function getResourceIdAttribute()
    {
        if ($this->location_id) {
            return $this->location_id;
        }
        if (! empty($this->site_id)) {
            return '0_'.$this->site_id;
        }

        return '0';
    }

    public function getSequenceAttribute()
    {
        return $this->program->meetings->where("start_datetime", "<=", $this->start_datetime)->count();
    }

    public function getTotalMeetingsAttribute()
    {
        return $this->program->meetings->count();
    }
}

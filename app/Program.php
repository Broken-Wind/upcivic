<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;
use Upcivic\Concerns\HasDatetimeRange;
use Carbon\Carbon;
use Upcivic\Concerns\Filterable;
use DB;
use Illuminate\Database\Eloquent\Builder;

class Program extends Model
{
    use HasDatetimeRange;

    use Filterable;
    //
    protected $fillable = [

        'name',

        'internal_name',

        'description',

        'public_notes',

        'contributor_notes',

        'invoice_amount',

        'invoice_type',

        'ages_type',

        'min_age',

        'max_age',

    ];

    public static function boot()
    {

        parent::boot();

        static::addGlobalScope('ExcludePastPrograms', function (Builder $builder) {

            return $builder->whereHas('meetings', function ($query) {

                return $query->where('end_datetime', '>', Carbon::now()->subDays(5));

            });

        });
    }

    public static function createExample($organization)
    {

        $exampleOrg = Organization::where('slug', 'example')->firstOrFail();

        $proposal = [

            'organization_id' => $exampleOrg['id'],

            'start_time' => '09:00',

            'start_date' => Carbon::now()->addDays(30)->format('Y-m-d'),

            'template_id' => $exampleOrg->templatesWithoutScope()->first(),

            'proposer_id' => $organization['id'],

        ];

        $template = $exampleOrg->templatesWithoutScope()->first();

        return static::fromTemplate($proposal, $template);


    }

    public static function fromTemplate($proposal, $template = null)
    {
        DB::transaction(function () use ($proposal, $template) {

            if ($proposal['start_date'] && $proposal['start_time']) {

                $template = $template ?? Template::find($proposal['template_id']);

                $program = Program::create([

                    'name' => $template['name'],

                    'description' => $template['description'],

                    'public_notes' => $template['public_notes'],

                    'contributor_notes' => $template['contributor_notes'],

                    'ages_type' => $proposal['ages_type'] ?? $template['ages_type'],

                    'min_age' => $proposal['min_age'] ?? $template['min_age'],

                    'max_age' => $proposal['max_age'] ?? $template['max_age'],

                ]);

                $proposer = new Contributor([

                    'internal_name' => $template['internal_name'],

                    'invoice_amount' => $template['invoice_amount'],

                    'invoice_type' => $template['invoice_type'],

                ]);

                $proposer['program_id'] = $program['id'];

                $proposer['organization_id'] = $proposal['proposer_id'] ?? tenant()['id'];

                $proposer->save();


                $contributor = new Contributor([]);

                $contributor['program_id'] = $program['id'];

                $contributor['organization_id'] = $proposal['organization_id'];

                if ($contributor['organization_id'] != $proposer['organization_id']) {

                    $contributor->save();

                }


                $startTime = $proposal['start_time'];

                $endTime = $proposal['end_time'] ?? date('H:i:s', strtotime($proposal['start_time'] . " +" . $template['meeting_minutes'] . " minutes"));

                $currentStartDatetime = date('Y-m-d H:i:s', strtotime($proposal['start_date'] . " " . $startTime));

                $currentEndDatetime = date('Y-m-d H:i:s', strtotime($proposal['start_date'] . " " . $endTime));

                if (!empty($proposal['end_date'])) {

                    $lastStartDatetime = date('Y-m-d H:i:s', strtotime($proposal['end_date'] . " " . $startTime));

                } else {

                    $lastStartDatetime = date('Y-m-d H:i:s', strtotime(\Carbon\Carbon::parse($proposal['start_date'])->addDays($template['meeting_count'] * $template['meeting_interval'])));

                }

                for ($currentStartDatetime; $currentStartDatetime <= $lastStartDatetime; ($currentStartDatetime = date('Y-m-d H:i:s', strtotime($currentStartDatetime . " +" . $template['meeting_interval'] . "days")))) {

                    $meeting = new Meeting([

                        'start_datetime' => $currentStartDatetime,

                        'end_datetime' => $currentEndDatetime

                    ]);

                    $meeting['program_id'] = $program['id'];

                    $meeting['site_id'] = $proposal['site_id'] ?? null;

                    $meeting->save();


                    $currentEndDatetime = date('Y-m-d H:i:s', strtotime($currentEndDatetime . " +" . $template['meeting_interval'] . " days"));

                }

            }

        });

    }

    public function getSharedInvoiceTypeAttribute()
    {

        return $this->contributors->where('invoice_type', '!==', null)->pluck('invoice_type')->unique()->count() == 1 ? $this->contributors[0]['invoice_type'] : null;

    }

    public function getNextMeetingStartDatetimeAttribute()
    {

        return $this->meetings->sortByDesc('start_datetime')->first()['start_datetime']->addDays($this['meeting_interval'])->format('Y-m-d\TH:i');

    }

    public function getNextMeetingEndDatetimeAttribute()
    {

        return $this->meetings->sortByDesc('start_datetime')->first()['end_datetime']->addDays($this['meeting_interval'])->format('Y-m-d\TH:i');

    }

    public function getMeetingIntervalAttribute()
    {

        $meetings = $this->meetings->sortBy('start_datetime');

        $intervals = collect([]);

        for ($i = 0; $i < $meetings->count()-1; $i++){

            $intervals->push($meetings[$i]['start_datetime']->diffInDays($meetings[$i + 1]['start_datetime']));

        }

        return count($intervals->mode()) < $meetings->count() / 3 ? $intervals->mode()[0] : null;

    }

    public function getDescriptionOfMeetingsAttribute()
    {

        return $this['day'] . " " . $this['start_date'] . '-' . $this['end_date'] . ' (' . $this->meetings->count() . ' meetings)';

    }


    public function getDayAttribute()
    {


        $days = $this->meetings->map(function ($meeting) {

            return $meeting['start_datetime']->format('l');

        });

        return $days->unique()->count() < 2 ? $days->mode()[0] . 's' : null;

    }

    public function delete()
    {

        $this->meetings()->delete();

        $this->contributors()->delete();

        parent::delete();

    }

    public function getFormattedBaseFeeAttribute()
    {

        return number_format($this->contributors->pluck('invoice_amount')->sum() / 100, 2);
    }

    public function getSiteAttribute () {

        $sites = collect([]);

        foreach ($this->meetings as $meeting) {

            $sites->push($meeting->site);

        }

        return $sites->where('name', $sites->mode('name')[0])->first();

    }

    public function getLocationAttribute () {

        $location = collect([]);

        foreach ($this->meetings as $meeting) {

            $location->push($meeting->location);

        }

        return $location->where('name', $location->mode('name')[0])->first();

    }

    public function meetings()
    {

        return $this->hasMany(Meeting::class);

    }

    public function contributors()
    {

        return $this->hasMany(Contributor::class);

    }

    public function getInternalNameAttribute()
    {

        return $this->contributors->where('organization_id', tenant()['id'])->first()['internal_name'] ?? $this['name'];

    }

    public function setInternalNameAttribute($internalName)
    {

        return $this->contributors->where('organization_id', tenant()['id'])->first()->update(['internal_name' => $internalName]);

    }

    public function getStartDatetimeAttribute()
    {

        return $this->firstMeeting()['start_datetime'];

    }

    public function getEndDatetimeAttribute()
    {

        return $this->lastMeeting()['end_datetime'];

    }

    public function firstMeeting()
    {

        return $this->meetings->sortBy('start_datetime')->first();

    }

    public function lastMeeting()
    {

        return $this->meetings->sortByDesc('start_datetime')->first();

    }

}

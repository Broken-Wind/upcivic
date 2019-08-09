<?php

namespace Upcivic;

use Illuminate\Database\Eloquent\Model;
use Upcivic\Concerns\HasDatetimeRange;
use Carbon\Carbon;

class Program extends Model
{
    use HasDatetimeRange;
    //
    protected $fillable = [

        'name',

        'internal_name',

        'description',

        'invoice_amount',

        'invoice_type',

        'ages_type',

        'min_age',

        'max_age',

    ];

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

        if ($proposal['start_date'] && $proposal['start_time']) {

            $template = $template ?? Template::find($proposal['template_id']);

            $program = Program::create([

                'name' => $template['name'],

                'description' => $template['description'],

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

    }

    public function contributorsShareInvoiceType()
    {

        return $this->contributors->where('invoice_type', '!==', null)->pluck('invoice_type')->unique()->count() == 1;

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

        return $this->contributors()->where('organization_id', tenant()['id'])->first()['internal_name'] ?? $this['name'];

    }

    public function setInternalNameAttribute($internalName)
    {

        return $this->contributors()->where('organization_id', tenant()['id'])->first()->update(['internal_name' => $internalName]);

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

        return $this->meetings()->orderBy('start_datetime')->first();

    }

    public function lastMeeting()
    {

        return $this->meetings()->orderByDesc('start_datetime')->first();

    }

}

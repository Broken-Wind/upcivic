<?php

namespace App;

use App\Concerns\Filterable;
use App\Concerns\HasDatetimeRange;
use App\Mail\ProposalSent;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function GuzzleHttp\Psr7\_caseless_remove;

class Program extends Model
{
    use HasDatetimeRange;
    use Filterable;
    //
    public const STATUSES = [
        'unsent' => [
            'event_color' => '#6c757d',
            'class_string' => 'alert-secondary',
            'status_string' => 'Unsent'
        ],
        'proposed' => [
            'event_color' => '#3490dc',
            'class_string' => 'alert-primary',
            'status_string' => 'Proposed'
        ],
        'approved' => [
            'event_color' => '#38c172',
            'class_string' => 'alert-success',
            'status_string' => 'Approved'
        ],
        'will_publish' => [
            'event_color' => '#ffed4a',
            'class_string' => 'alert-warning',
            'status_string' => 'Scheduled to publish'
        ],
        'published' => [
            'event_color' => '#343a40',
            'class_string' => 'alert-dark',
            'status_string' => 'Published'
        ],
    ];

    protected $dates = ['proposed_at'];

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
        'min_enrollments',
        'enrollments',
        'max_enrollments',
        'proposing_organization_id',
        'proposed_at',
    ];

    public function scopeExcludePast($query)
    {
        return $query->whereHas('meetings', function ($query) {
            return $query->where('end_datetime', '>', Carbon::now()->subDays(5));
        });
    }

    public function scopePublishedForTenant($query)
    {
        return $query->whereHas('contributors', function ($query) {
            return $query->where('organization_id', tenant()['organization_id'])->where('published_at', '<=', now());
        });
    }

    public function scopeUnpublishedForTenant($query)
    {
        return $query->whereHas('contributors', function ($query) {
            return $query->where('organization_id', tenant()['organization_id'])->where('published_at', '<=', now());
        });
    }

    public static function groupPrograms($programs)
    {
        if (tenant()->organization->hasAreas()) {
            return self::groupProgramsWithAreas($programs);
        }
        return self::groupProgramsWithoutAreas($programs);
    }

    public static function groupProgramsWithAreas($programs)
    {
        $grouped = $programs->groupBy([
            function ($program) {
                return $program->start_date;
            },
            function ($program) {
                return $program->area->name;
            },
            function ($program) {
                return $program->site->name;
            }
        ]);
        $sorted = $grouped->transform(function ($startDate) {
            $startDate->transform(function ($area) {
                $area->transform(function ($site) {
                    return $site->sortBy('start_datetime')->sortBy('end_datetime');
                });
                return $area->sortBy(function ($programs, $site) {
                    return $site;
                });
            });
            return $startDate->sortBy(function ($sites, $area) {
                return $area;
            });
        })->sortBy(function ($areas, $startDate) {
            return $startDate;
        });
        return $sorted;
    }

    public static function groupProgramsWithoutAreas($programs)
    {
        $grouped = $programs->groupBy([
            function ($program) {
                return $program->start_date;
            },
            function ($program) {
                return $program->site->name;
            }
        ]);
        $sorted = $grouped->transform(function ($startDate) {
            $startDate->transform(function ($site) {
                return $site->sortBy('start_datetime')->sortBy('end_datetime');
            });
            return $startDate->sortBy(function ($programs, $site) {
                return $site;
            });
        })->sortBy(function ($sites, $startDate) {
            return $startDate;
        });
        return $sorted;
    }

    public function getAreaAttribute()
    {
        return $this->site->area;
    }

    public function getContributorFromTenant($tenant = null)
    {
        if (! empty($tenant)) {
            $organizationId = $tenant['organization_id'];
        } else {
            $organizationId = tenant()['organization_id'];
        }

        return $this->contributors->where('organization_id', $organizationId)->first();
    }

    public function isPublished()
    {
        return $this->getContributorFromTenant()->isPublished();
    }

    public function isApprovedByAllContributors()
    {
        return $this->contributors->where('approved_at', null)->count() == 0;
    }

    public function isWillPublishByAllContributors()
    {
        return $this->contributors->where('published_at', null)->count() == 0;
    }

    public function isPublishedByAllContributors()
    {
        return $this->contributors->filter(function ($contributor) {
            return !empty($contributor['published_at']) && $contributor['published_at'] <= Carbon::now();
        })->count() == $this->contributors->count();
    }

    public function isProposalSent()
    {
        if (!empty($this['proposed_at'])) {
            return true;
        }
        return false;
    }

    public function canBePublished()
    {
        if ($this->isProposalSent() && $this->isApprovedByAllContributors()) {
            return true;
        }
        return false;
    }

    public function willPublish()
    {
        return $this->getContributorFromTenant()->willPublish();
    }

    public function getPublishedAtAttribute()
    {
        return $this->getContributorFromTenant()->published_at;
    }

    public static function createExample($organization)
    {
        $exampleOrg = Organization::where('name', 'Exampleville Parks & Recreation')->firstOrFail();
        $proposal = [
            'recipient_organization_id' => $exampleOrg['id'],
            'start_time' => '09:00',
            'start_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'template_id' => $exampleOrg->templatesWithoutScope()->first(),
            'proposing_organization_id' => $organization['id'],
        ];
        $template = $exampleOrg->templatesWithoutScope()->first();

        return static::fromTemplate($proposal, $template);
    }

    public static function fromTemplate($proposal, $template = null)
    {
        $program = null;
        DB::transaction(function () use ($proposal, $template, &$program) {
            if ($proposal['start_date'] && $proposal['start_time']) {
                $template = $template ?? Template::find($proposal['template_id']);
                $program = self::create([
                    'name' => $template['name'],
                    'description' => $template['description'],
                    'public_notes' => $template['public_notes'],
                    'contributor_notes' => $template['contributor_notes'],
                    'ages_type' => $proposal['ages_type'] ?? $template['ages_type'],
                    'min_age' => $proposal['min_age'] ?? $template['min_age'],
                    'max_age' => $proposal['max_age'] ?? $template['max_age'],
                    'min_enrollments' => $template['min_enrollments'],
                    'max_enrollments' => $template['max_enrollments'],
                    'proposing_organization_id' => $proposal['proposing_organization_id'] ?? tenant()->organization_id,
                    'proposed_at' => $proposal['proposed_at'] ?? null,
                ]);
                $proposingContributor = new Contributor([
                    'internal_name' => $template['internal_name'],
                    'invoice_amount' => $template['invoice_amount'],
                    'invoice_type' => $template['invoice_type'],
                ]);
                $proposingContributor['program_id'] = $program['id'];
                $proposingContributor['organization_id'] = $proposal['proposing_organization_id'] ?? tenant()->organization_id;
                $proposingContributor->save();

                $contributor = new Contributor([]);
                $contributor['program_id'] = $program['id'];
                $contributor['organization_id'] = $proposal['recipient_organization_id'];
                if ($contributor['organization_id'] != $proposingContributor['organization_id']) {
                    $contributor->save();
                }
                $startTime = $proposal['start_time'];
                $endTime = $proposal['end_time'] ?? date('H:i:s', strtotime($proposal['start_time'].' +'.$template['meeting_minutes'].' minutes'));
                $currentStartDatetime = date('Y-m-d H:i:s', strtotime($proposal['start_date'].' '.$startTime));
                $currentEndDatetime = date('Y-m-d H:i:s', strtotime($proposal['start_date'].' '.$endTime));
                if (! empty($proposal['end_date'])) {
                    $lastStartDatetime = date('Y-m-d H:i:s', strtotime($proposal['end_date'].' '.$startTime));
                } else {
                    $lastStartDatetime = date('Y-m-d H:i:s', strtotime(\Carbon\Carbon::parse($proposal['start_date'])->addDays($template['meeting_count'] * $template['meeting_interval'])));
                }
                for ($currentStartDatetime; $currentStartDatetime <= $lastStartDatetime; ($currentStartDatetime = date('Y-m-d H:i:s', strtotime($currentStartDatetime.' +'.$template['meeting_interval'].'days')))) {
                    $meeting = new Meeting([
                        'start_datetime' => $currentStartDatetime,
                        'end_datetime' => $currentEndDatetime,
                    ]);
                    $meeting['program_id'] = $program['id'];
                    $meeting['site_id'] = $proposal['site_id'] ?? null;
                    $meeting['location_id'] = $proposal['location_id'] ?? null;
                    $meeting->save();

                    $currentEndDatetime = date('Y-m-d H:i:s', strtotime($currentEndDatetime.' +'.$template['meeting_interval'].' days'));
                }
                $organizations = $program->contributors->map(function ($contributor) {
                    return $contributor->organization;
                });
            }
        });

        return $program;
    }

    public function getResourceIdAttribute()
    {
        $locationIds = $this->meetings->pluck('location_id')->filter(function ($locationId) {
            return ! empty($locationId);
        });
        if ($locationIds->isNotEmpty()) {
            return $locationIds->mode();
        }
        $siteId = $this->site->id ?? null;
        if (! empty($siteId)) {
            return '0_'.$siteId;
        }

        return '0';
    }

    public function getSharedInvoiceTypeAttribute()
    {
        return $this->contributors->whereNotNull('invoice_type')->pluck('invoice_type')->unique()->count() == 1 ? $this->contributors[0]['invoice_type'] : null;
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
        if ($this->meetings->count() < 2) {
            return 1;
        }
        $meetings = $this->meetings->sortBy('start_datetime');
        $intervals = collect([]);
        for ($i = 0; $i < $meetings->count() - 1; $i++) {
            $intervals->push($meetings[$i]['start_datetime']->diffInDays($meetings[$i + 1]['start_datetime']));
        }

        return count($intervals->mode()) < $meetings->count() / 3 ? $intervals->mode()[0] : null;
    }

    public function getDescriptionOfMeetingsAttribute()
    {
        return $this['day'].' '.$this['start_date'].'-'.$this['end_date'].' ('.$this->meetings->count().' meetings)';
    }

    public function getDescriptionOfAgeRangeAttribute()
    {
        return ucfirst("{$this['ages_type']} {$this['min_age']} to {$this['max_age']}");
    }

    public function getDayAttribute()
    {
        $days = $this->meetings->map(function ($meeting) {
            return $meeting['start_datetime']->format('l');
        });

        return $days->unique()->count() < 2 ? $days->mode()[0].'s' : null;
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

    public function getSiteAttribute()
    {
        $sites = collect([]);
        foreach ($this->meetings as $meeting) {
            $sites->push($meeting->site);
        }

        return $sites->where('name', $sites->mode('name')[0])->first();
    }

    public function getLocationAttribute()
    {
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

    public function getInstructorsAttribute()
    {
        return $this->meetings->map(function ($meeting) {
            return $meeting->instructors->pluck('first_name');
        })->flatten()->unique();
    }

    public function addInstructor(Instructor $instructor)
    {
        $this->meetings->each(function ($meeting) use ($instructor) {
            $meeting->instructors()->syncWithoutDetaching($instructor);
        });
    }

    public function removeInstructor(Instructor $instructor)
    {
        $this->meetings->each(function ($meeting) use ($instructor) {
            $meeting->instructors()->detach($instructor);
        });
    }

    public function hasUnstaffedMeetings()
    {
        return $this->meetings->every(function ($meeting) {
            return $meeting->instructors->isNotEmpty();
        }) ? false : true;
    }

    public function contributors()
    {
        return $this->hasMany(Contributor::class);
    }

    public function otherContributors()
    {
        return $this->contributors->where('organization_id', '!=', tenant()['organization_id']);
    }

    public function recipientContributors()
    {
        return $this->contributors->where('organization_id', '!=', $this->proposing_organization_id);
    }

    public function hasOtherContributors()
    {
        return $this->otherContributors()->count() > 0;
    }

    public function getRegistrationContactsAttribute()
    {
        if (! $this->hasOtherContributors()) {
            return tenant()->organization->administrators;
        }

        return $this->otherContributors()->map(function ($contributor) {
            return $contributor->organization->administrators;
        })->flatten(1);
    }

    public function getInternalNameAttribute()
    {
        return $this->contributors->where('organization_id', tenant()->organization_id)->first()['internal_name'] ?? $this['name'];
    }

    public function setInternalNameAttribute($internalName)
    {
        return $this->contributors->where('organization_id', tenant()->organization_id)->first()->update(['internal_name' => $internalName]);
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

    public function proposer()
    {
        return $this->belongsTo(Organization::class, 'proposing_organization_id');
    }

    public function isProposed()
    {
        return $this->proposed_at != null;
    }

    public function getStatus()
    {
        switch (true) {
            // case ($this->isPublishedByAllContributors()):
            //     return 'published';
            // case ($this->isWillPublishByAllContributors()):
            //     return 'will_publish';
            case ($this->isApprovedByAllContributors()):
                return 'approved';
            case ($this->isProposed()):
                return 'proposed';
            default:
                return 'unsent';
        }
    }

    public function getEventColorAttribute(){
        return self::STATUSES[$this->getStatus()]['event_color'];
    }

    public function getClassStringAttribute(){
        return self::STATUSES[$this->getStatus()]['class_string'];
    }

    public function getAgesStringAttribute()
    {
        return ucfirst($this->ages_type) . ' ' . $this->min_age . '-' . $this->max_age;
    }

    public function isFullyApproved() {
        return $this->contributors->where('approved_at', null)->count() == 0;
    }

    public function getStatusClassStringAttribute()
    {
        return self::STATUSES[$this->getStatus()]['class_string'];
    }

    public function getStatusStringAttribute()
    {
        return self::STATUSES[$this->getStatus()]['status_string'];
    }
    public function getStatusDescriptionAttribute()
    {
        $statusStrings = [
            'unsent' => 'This proposal is not yet sent.',
            'proposed' => 'Proposed by ' . $this->proposer->name,
            'approved' => 'This program is fully approved.',
            // 'will_publish' => !empty($this->published_at) ? 'You\'re publishing this on ' . $this->published_at->format('m/d/Y') : 'Status error.',
            // 'published' => 'This program is now listed on your <a href="' . tenant()->route('tenant:admin.edit') . '#publishing">iFrame widget.</a>',
        ];
        return $statusStrings[$this->getStatus()];
    }
}

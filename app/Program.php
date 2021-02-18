<?php

namespace App;

use App\Exceptions\CannotManuallyUpdateInternalRegistrationsException;
use App\Http\Concerns\Filterable;
use App\Http\Concerns\HasDatetimeRange;
use App\Mail\ProposalSent;
use App\Exceptions\NotEnoughTicketsException;
use Carbon\Carbon;
use DateTime;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

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
                if ($area == 'Other/Unspecified Area') {
                    return '~'; //Sort last
                }
                return $area;
            });
        })->sortBy(function ($areas, $startDate) {
            // Start dates in the format d/m/Y would not sort correctly, so we must convert them to a sortable form.
            // We use the built-in function instead of Carbon as I believe it is higher performance
            return DateTime::createFromFormat("d/m/Y H:i:s", $startDate);
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
            // Start dates in the format d/m/Y would not sort correctly, so we must convert them to a sortable form.
            // We use the built-in function instead of Carbon as I believe it is higher performance
            return DateTime::createFromFormat("d/m/Y H:i:s", $startDate);
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
        if (!empty($this['proposed_at']) || $this->otherContributors()->isEmpty()) {
            return true;
        }
        return false;
    }

    public function canBePublished()
    {
        if ($this->isProposalSent() && $this->isApprovedByAllContributors()) {
            return true;
        }
        if ($this->hasOneContributor()) {
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

    public function getParticipantsAttribute()
    {
        return $this->tickets->map(function ($ticket) {
            return $ticket->participant;
        })->filter(function ($participant) {
            return !empty($participant);
        });
    }

    public function shouldDisplayMap()
    {
        return !empty($this->site->id) && !$this->isVirtual();
    }

    public function isVirtual()
    {
        return $this->site->name == '[VIRTUAL]';
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
                    'proposing_organization_id' => $proposal['proposing_organization_id'] ?? tenant()->organization_id,
                    'proposed_at' => $proposal['proposed_at'] ?? null,
                ]);
                $program->addTickets($template['max_enrollments']);
                $proposingContributor = new Contributor([
                    'internal_name' => $template['internal_name'],
                    'invoice_amount' => $template['invoice_amount'],
                    'invoice_type' => $template['invoice_type'],
                ]);
                $proposingContributor['program_id'] = $program['id'];
                $proposingContributor['organization_id'] = $proposal['proposing_organization_id'] ?? tenant()->organization_id;
                $proposingContributor->save();
                if (!empty($proposal['recipient_organization_id'])) {
                    $contributor = new Contributor([]);
                    $contributor['program_id'] = $program['id'];
                    $contributor['organization_id'] = $proposal['recipient_organization_id'];
                    if ($contributor['organization_id'] != $proposingContributor['organization_id']) {
                        $contributor->save();
                    }
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

    public function maxTicketOrder()
    {
        return min($this->tickets()->available()->count(), 4);
    }


    public function updateEnrollments($enrollments, $maxEnrollments) {
        if ($this->allowsRegistration()) {
            throw new CannotManuallyUpdateInternalRegistrationsException();
        }
        if ($enrollments > $this->max_enrollments) {
            $this->setMaxEnrollments($maxEnrollments);
            $this->setEnrollments($enrollments);
        } else  {
            $this->setEnrollments($enrollments);
            $this->setMaxEnrollments($maxEnrollments);
        }
        // $this->update([
        //     'enrollments_updated' => now(),
        // ]);
    }

    public function setEnrollments($updatedEnrollments) {
        $diff = $updatedEnrollments - $this->fresh()->enrollments;
        if ($diff > 0) {
            return $this->claimAnonymousTickets($diff);
        }
        return $this->releaseAnonymousTickets(abs($diff));
    }

    public function setMaxEnrollments($updatedMaxEnrollments) {
        $diff = $updatedMaxEnrollments - $this->fresh()->max_enrollments;
        if ($diff > 0) {
            return $this->addTickets($diff);
        }
        return $this->removeTickets(abs($diff));
    }

    public function removeTickets($diff)
    {
        $availableTickets = $this->tickets()->available()->get()->count();
        $anonymousTickets = $this->tickets()->anonymous()->get()->count();
        if ($diff > ($availableTickets + $anonymousTickets)) {
            throw new Exception('Error updating enrollments');
        }
        $this->tickets()->available()->take($diff)->delete();
        if ($diff > $availableTickets) {
            $this->tickets()->anonymous()->take($diff - $availableTickets)->delete();
        }
    }

    public function claimAnonymousTickets($diff)
    {
        $this->tickets()->available()->take($diff)->update(['order_id' => 0]);
    }

    public function releaseAnonymousTickets($diff)
    {
        $this->tickets()->unavailable()->take($diff)->update(['order_id' => null]);
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

    public function getMaxEnrollmentsAttribute()
    {
        return $this->tickets->count();
    }

    public function getEnrollmentsAttribute()
    {
        return $this->tickets->filter(function ($ticket) {
            return $ticket->order_id !== null;
        })->count();
    }

    public function getEnrollmentPercentAttribute()
    {
        if (empty($this->max_enrollments)) {
            return 0;
        }
        return 100 * ($this->enrollments / $this->max_enrollments);
    }

    public function getProgressBarClassAttribute()
    {
        if ($this->isFull()) {
            return 'bg-success';
        }
        if ($this->isAboveMinimum()){
            return 'bg-primary';
        }
        return 'bg-danger';
    }

    public function isFull()
    {
        return $this->enrollments >= $this->max_enrollments;
    }

    public function isAboveMinimum()
    {
        return $this->enrollments >= $this->min_enrollments;
    }

    public function getSuggestedEnrollmentUrlAttribute()
    {
        return $this->enrollment_url
            ?? tenant()->organization->enrollment_url
            ?? $this->contributors->map(function ($contributor) {
                return $contributor->organization->enrollment_url;
            })->whereNotNull()->first()
            ?? null;
    }

    public function getSuggestedEnrollmentInstructionsAttribute()
    {
        return $this->enrollment_instructions
            ?? tenant()->organization->enrollment_instructions
            ?? $this->contributors->map(function ($contributor) {
                return $contributor->organization->enrollment_instructions;
            })->whereNotNull()->first()
            ?? null;
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

    public function getFormattedPriceAttribute()
    {
        return isset($this->price) ? number_format($this->price / 100, 2, '.', '') : null;
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
            return $meeting->instructors;
        })->flatten()->unique('id');
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
        return tenant()->organization->hasInstructors()
            && !$this->meetings->every(function ($meeting) {
                return $meeting->instructors->isNotEmpty();
            });
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

    public function hasOneContributor()
    {
        return $this->contributors->count() == 1;
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
            case ($this->hasOneContributor()):
                return 'proposed';
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
            'proposed' => 'Proposed by ' . Purify::clean($this->proposer->name),
            'approved' => 'This program is fully approved.',
            // 'will_publish' => !empty($this->published_at) ? 'You\'re publishing this on ' . $this->published_at->format('m/d/Y') : 'Status error.',
            // 'published' => 'This program is now listed on your <a href="' . tenant()->route('tenant:admin.edit') . '#publishing">iFrame widget.</a>',
        ];
        return $statusStrings[$this->getStatus()];
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'tickets');
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function findTickets($quantity)
    {
        $tickets = $this->tickets()->available()->take($quantity)->get();

        if ($tickets->count() < $quantity) {
            throw new NotEnoughTicketsException;
        }

        return $tickets;
    }

    public function reserveTickets($quantity, $email)
    {
        $tickets = $this->findTickets($quantity)->each(function ($ticket)
        {
            $ticket->reserve();
        });

        return new Reservation($tickets, $email);
    }

    public function addTickets($quantity)
    {
        foreach (range(1, $quantity) as $i) {
            $this->tickets()->create([]);
        }

        return $this;
    }

    public function ticketsRemaining()
    {
        return $this->tickets()->available()->count();
    }

    public function hasOrderFor($customerEmail)
    {
        return $this->orders()->where('email', $customerEmail)->count() > 0;
    }

    public function ordersFor($customerEmail)
    {
        return $this->orders()->where('email', $customerEmail)->get();
    }

    public function allowsRegistration()
    {
        return $this->internal_registration;
    }

    public function hasEnrollmentUrl()
    {
        return !empty($this->enrollment_url);
    }

    public function hasEnrollments()
    {
        return !empty($this->tickets->firstWhere('reserved_at', '!=', null));
    }

    public function hasEnrollmentInstructions()
    {
        return !empty($this->enrollment_instructions);
    }
}

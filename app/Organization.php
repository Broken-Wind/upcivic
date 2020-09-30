<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function scopePartneredWith($query, $organizationId)
    {
        return $query->whereHas('programs', function ($query) use ($organizationId) {
            return $query->whereHas('contributors', function ($query) use ($organizationId) {
                return $query->where('organization_id', $organizationId);
            })->whereNotNull('proposed_at');
        })->where('id', '!=', $organizationId);
    }

    public function scopeEmailable($query)
    {
        return $query->whereHas('administrators', function ($query) {
            return $query->whereNotNull('email');
        });
    }
    public function outgoingAssignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_by_organization_id');
    }
    public function incomingAssignments()
    {
        return $this->hasMany(Assignment::class, 'assigned_to_organization_id');
    }
    public function outgoingAssignmentsForInstructors()
    {
        return $this->hasMany(Assignment::class, 'assigned_by_organization_id')->forInstructors();
    }
    public function incomingAssignmentsForInstructors()
    {
        return $this->hasMany(Assignment::class, 'assigned_to_organization_id')->forInstructors();
    }
    public function assignedInstructors()
    {
        return $this->belongsToMany(Instructor::class, 'assigned_instructors');
    }

    public function assignInstructorTasksTo($instructorId)
    {
        $this->outgoingAssignmentsForInstructors->each(function ($assignment) use ($instructorId) {
            $assignment->assignToInstructor($instructorId);
        });
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'contributors');
    }
    public function instructors()
    {
        return $this->hasMany(Instructor::class);
    }

    public function administrators()
    {
        return $this->belongsToMany(Person::class, 'administrators')->as('administrator')->withPivot('title');
    }

    public function emailableContacts()
    {
        return $this->administrators;
    }

    public function hasAdministratorEmail($email)
    {
        return $this->administrators->pluck('email')->contains($email);
    }

    public function hasEmailableContacts()
    {
        return $this->administrators->count() > 0 ? true : false;
    }

    public function isVacant()
    {
        $contacts = $this->administrators->count();

        if (isset($this->tenant)) {
            $contacts += $this->tenant->users->count();
        }

        return $contacts == 0;
    }

    public function hasTenant()
    {
        return isset($this->tenant);
    }

    public function isClaimed()
    {
        return isset($this->tenant) && $this->tenant->users->count() > 0;
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function getUsersAttribute()
    {
        return $this->tenant->users;
    }

    public function getProposalNextStepsAttribute()
    {
        return $this->tenant->proposal_next_steps;
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function templatesWithoutScope()
    {
        return $this->hasMany(Template::class)->withoutGlobalScopes();
    }

    public function proposals()
    {
        return $this->hasMany(Program::class, 'proposing_organization_id');
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }
}

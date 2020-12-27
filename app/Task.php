<?php

namespace App;

use App\Mail\AssignmentSent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Task extends Model
{
    //

    protected $fillable = [
        'name',
        'description',
    ];

    public function assign($organizationId, $programIds = [])
    {
        $assignment = Assignment::make([
            'name' => $this->name,
            'description' => $this->description
        ]);
        $assignment->assign_to_entity = $this->assign_to_entity;
        $assignment->assigned_by_organization_id = tenant()->organization_id;
        $assignment->assigned_to_organization_id = $organizationId;
        $assignment->task_id = $this->id;
        $assignment->save();
        $assignedToOrganization = Organization::find($organizationId);
        switch ($assignment->assign_to_entity) {
            case Instructor::class:
                tenant()->organization->instructorsAssignedBy($assignedToOrganization)->each(function ($instructor) use ($assignment) {
                    $assignment->assignToInstructor($instructor->id);
                });
                break;
            default:
                $assignment->statusModel()->create([]);
                break;
        }
        if ($this->type == 'signable_document') {
            $assignment->signableDocument()->create([
                'title' => $this->signableDocument->title,
                'content' => $this->signableDocument->content,
                'program_ids' => $programIds
            ]);
            $emailButtonLink = URL::signedRoute('tenant:assignments.sign', ['tenant' => tenant()->slug, 'assignment' => $assignment]);
        } else {
            $emailButtonLink = tenant()->route('tenant:admin.assignments.incoming.index');
        }
        $sender = Auth::user();
        $assignedByOrganization = tenant()->organization;
        \Mail::send(new AssignmentSent($assignment, $sender, $assignedByOrganization, $assignedToOrganization, $emailButtonLink));
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function signableDocument()
    {
        return $this->hasOne(SignableDocument::class);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'entity_id')->entity($this);
    }
    public function getAccessibleOrganizationsAttribute()
    {
        return $this->assignments->map(function ($assignment) {
            return [$assignment->assignedToOrganization, $assignment->assignedByOrganization];
        })->flatten()->unique();
    }
}

<?php

namespace App;

use App\Mail\AssignmentSent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    //

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function assign($organizationId, $metadata = null)
    {
        $assignment = Assignment::make([
            'name' => $this->name,
            'description' => $this->description
        ]);
        $assignment->assign_to_entity = $this->assign_to_entity;
        $assignment->assigned_by_organization_id = tenant()->organization_id;
        $assignment->assigned_to_organization_id = $organizationId;
        $assignment->task_id = $this->id;
        $metadata = array_merge($this->metadata, $metadata);
        $assignment->metadata = $metadata;
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
        $sender = Auth::user();
        $assignedByOrganization = tenant()->organization;
        \Mail::send(new AssignmentSent($assignment, $sender, $assignedByOrganization, $assignedToOrganization));
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
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

<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAssignments;
use App\Instructor;
use App\Organization;
use App\Task;
use Illuminate\Http\Request;
use Mixpanel;

class TaskAssignmentController extends Controller
{
    //

    public function massUpdate(Task $task, UpdateAssignments $request)
    {
        $validated = $request->validated();
        if (empty($validated['assignToOrganizationIds'])) {
            Assignment::withoutGlobalScope('OrganizationAssignment')->outgoing()->where('task_id', $task->id)->delete();
        } else {
            Assignment::withoutGlobalScope('OrganizationAssignment')->outgoing()->where('task_id', $task->id)->whereNotIn('assigned_to_organization_id', $validated['assignToOrganizationIds'])->delete();
            $alreadyAssignedOrganizations = Assignment::outgoing()->where('task_id', $task->id)->whereIn('assigned_to_organization_id', $validated['assignToOrganizationIds'])->get()->pluck('assigned_to_organization_id');
            $organizationsToAssign = collect($validated['assignToOrganizationIds'])->diff($alreadyAssignedOrganizations);
            $organizationsToAssign->each(function ($organizationId) use ($task) {
                $assignment = Assignment::make([
                    'name' => $task->name,
                    'description' => $task->description
                ]);
                $assignment->assign_to_entity = $task->assign_to_entity;
                $assignment->assigned_by_organization_id = tenant()->organization_id;
                $assignment->assigned_to_organization_id = $organizationId;
                $assignment->task_id = $task->id;
                $assignment->save();
                switch ($assignment->assign_to_entity) {
                    case Instructor::class:
                        $organization = Organization::find($organizationId);
                        tenant()->organization->instructorsAssignedBy($organization)->each(function ($instructor) use ($assignment) {
                            $assignment->assignToInstructor($instructor->id);
                        });
                        break;
                    default:
                        $assignment->statusModel()->create([]);
                        break;
                }
            });
        }
        return back()->withSuccess('Assignments updated!');
    }
}

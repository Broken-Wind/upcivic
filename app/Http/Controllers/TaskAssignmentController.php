<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAssignments;
use App\Task;
use Illuminate\Http\Request;

class TaskAssignmentController extends Controller
{
    //

    public function massUpdate(Task $task, UpdateAssignments $request)
    {
        $validated = $request->validated();
        Assignment::outgoing()->where('task_id', $task->id)->whereNotIn('assigned_to_organization_id', $validated['assignToOrganizationIds'])->delete();
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
        });
        return back()->withSuccess('Assignments updated!');
    }
}

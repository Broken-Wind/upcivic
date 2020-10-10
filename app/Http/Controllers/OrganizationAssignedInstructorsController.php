<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAssignedInstructors;
use App\Instructor;
use App\Organization;
use Illuminate\Http\Request;

class OrganizationAssignedInstructorsController extends Controller
{
    //
    public function massUpdate(Organization $organization, UpdateAssignedInstructors $request)
    {
        $validated = $request->validated();
        if (empty($validated['assignInstructorIds'])) {
            $organization->incomingAssignedInstructors()->each(function ($instructor) use ($organization) {
                $instructor->incomingAssignmentsFrom($organization)->delete();
            });
            $organization->incomingAssignedInstructors()->detach();
        } else {
            $organization->incomingAssignedInstructors()->whereNotIn('instructors.id', $validated['assignInstructorIds'])->get()->each(function ($instructor) use ($organization) {
                $instructor->incomingAssignmentsFrom($organization)->each(function ($assignment) {
                    $assignment->delete();
                });
            });
            $instructorIdsToDetach = $organization->incomingAssignedInstructors()->whereNotIn('instructors.id', $validated['assignInstructorIds'])->pluck('instructors.id');
            $organization->incomingAssignedInstructors()->detach($instructorIdsToDetach);
            $alreadyAssignedInstructorIds = $organization->incomingAssignedInstructors->pluck('id');
            $instructorIdsToAssign = collect($validated['assignInstructorIds'])->diff($alreadyAssignedInstructorIds);
            $organization->incomingAssignedInstructors()->attach($instructorIdsToAssign);
            $organization->outgoingAssignmentsForInstructors->each(function ($assignment) use ($instructorIdsToAssign) {
                $assignment->assignToInstructors($instructorIdsToAssign);
            });
        }
        return back()->withSuccess('Instructors updated!');
    }
}
